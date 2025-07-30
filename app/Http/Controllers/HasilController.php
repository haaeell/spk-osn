<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Siswa;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'penilai') {
            $mapels = [Auth::user()->kategori_mapel];
        } else {
            $mapels = ['mtk', 'ipa', 'ips'];
        }

        $hasilMapel = [];

        foreach ($mapels as $mapel) {
            $kriteria = Kriteria::where('mapel', $mapel)->get();
            if ($kriteria->isEmpty())
                continue;

            $siswa = Siswa::with([
                'penilaian' => function ($q) use ($mapel) {
                    $q->whereHas('kriteria', function ($q2) use ($mapel) {
                        $q2->where('mapel', $mapel);
                    });
                }
            ])->get();

            $dataHasil = [];

            foreach ($siswa as $s) {
                $nilai = [];
                foreach ($kriteria as $k) {
                    $n = $s->penilaian->firstWhere('id_kriteria', $k->id);
                    if (!$n)
                        continue 2;
                    $nilai[] = [
                        'id_kriteria' => $k->id,
                        'nilai' => $n->nilai
                    ];
                }

                $totalBobot = $kriteria->sum('bobot');
                $nilaiSmart = 0;

                foreach ($nilai as $n) {
                    $kri = $kriteria->firstWhere('id', $n['id_kriteria']);
                    $nilaiSmart += ($n['nilai'] * $kri->bobot) / 100;
                }

                $dataHasil[] = [
                    'nama' => $s->nama,
                    'kelas' => $s->kelas,
                    'nilai_akhir' => $nilaiSmart
                ];
            }

            usort($dataHasil, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);
            foreach ($dataHasil as $i => &$row) {
                $row['peringkat'] = $i + 1;
                $row['keterangan'] = $i == 0 ? '✅ Terpilih' : '❌ Tidak Terpilih';
            }

            $hasilMapel[$mapel] = $dataHasil;
        }

        return view('hasil.index', compact('hasilMapel'));
    }

    public function simpan(Request $request, $mapel)
    {
        $hasilMapel = $this->hitungSmart();

        if (!isset($hasilMapel[$mapel])) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        foreach ($hasilMapel[$mapel] as $row) {
            Hasil::updateOrCreate(
                ['id_siswa' => $row['id'], 'mapel' => $mapel],
                [
                    'nilai_akhir' => $row['nilai_akhir'],
                    'hasil' => json_encode([
                        'nama' => $row['nama'],
                        'kelas' => $row['kelas'],
                        'peringkat' => $row['peringkat'],
                        'keterangan' => $row['keterangan'],
                    ]),
                ]
            );
        }

        return back()->with('success', 'Hasil berhasil disimpan!');
    }

    public function hitungSmart()
    {
        $mapels = ['mtk', 'ipa', 'ips'];
        $hasilMapel = [];

        foreach ($mapels as $mapel) {
            $siswaIds = Penilaian::whereHas('kriteria', function ($q) use ($mapel) {
                $q->where('mapel', $mapel);
            })->pluck('id_siswa')->unique();

            $siswaList = Siswa::whereIn('id', $siswaIds)->get();
            $kriteriaList = Kriteria::where('mapel', $mapel)->get();

            $data = [];

            foreach ($siswaList as $siswa) {
                $nilai_akhir = 0;

                foreach ($kriteriaList as $kriteria) {
                    $penilaian = Penilaian::where('id_siswa', $siswa->id)
                        ->where('id_kriteria', $kriteria->id)
                        ->first();

                    $nilai = $penilaian ? $penilaian->nilai : 0;
                    $nilai_akhir += $nilai * $kriteria->bobot;
                }

                $data[] = [
                    'id' => $siswa->id,
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas,
                    'nilai_akhir' => $nilai_akhir,
                ];
            }

            usort($data, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

            foreach ($data as $i => &$d) {
                $d['peringkat'] = $i + 1;
                $d['keterangan'] = $i == 0 ? 'Terpilih' : 'Tidak Terpilih';
            }


            $hasilMapel[$mapel] = $data;
        }

        return $hasilMapel;
    }
}
