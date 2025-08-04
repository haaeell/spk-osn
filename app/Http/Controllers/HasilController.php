<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Siswa;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    public function index(Request $request)
    {
        $mapels = ['mtk', 'ipa', 'ips'];
        $kuota = [];
        foreach ($mapels as $m) {
            $kuota[$m] = (int) ($request->input("kuota.$m", 2));
        }

        $kriterias = [];
        foreach ($mapels as $mapel) {
            $kriterias[$mapel] = Kriteria::where('mapel', $mapel)->get();
        }

        $siswas = Siswa::with('penilaian.kriteria')->get();

        $hasilSementara = [];
        $siswaKurangNilai = [];

        foreach ($siswas as $siswa) {
            $nilaiPerMapel = [];

            foreach ($mapels as $mapel) {
                $totalBobot = $kriterias[$mapel]->sum('bobot');
                if ($totalBobot == 0) continue;

                $nilaiSmart = 0;

                foreach ($kriterias[$mapel] as $k) {
                    $penilaian = $siswa->penilaian->firstWhere('id_kriteria', $k->id);
                    if (!$penilaian) {
                        $siswaKurangNilai[] = "{$siswa->nama} (Mapel: {$mapel})";
                        continue 2; // langsung skip ke siswa berikutnya
                    }
                    $nilaiSmart += ($penilaian->nilai * $k->bobot) / 100;
                }

                $nilaiPerMapel[$mapel] = $nilaiSmart;
            }

            if (empty($nilaiPerMapel)) continue;

            $mapelTerbaik = array_keys($nilaiPerMapel, max($nilaiPerMapel))[0];
            $hasilSementara[$mapelTerbaik][] = [
                'id' => $siswa->id,
                'nama' => $siswa->nama,
                'kelas' => $siswa->kelas,
                'nilai_akhir' => $nilaiPerMapel[$mapelTerbaik]
            ];
        }

        $terpilih = [];
        $hasilMapel = [];

        foreach ($mapels as $mapel) {
            $siswaMapel = $hasilSementara[$mapel] ?? [];
            usort($siswaMapel, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

            $kuotaMapel = $kuota[$mapel] ?? 0;
            $ranking = 1;

            foreach ($siswaMapel as $s) {
                if (in_array($s['id'], $terpilih)) {
                    $s['peringkat'] = '-';
                    $s['keterangan'] = '❌ Sudah Terpilih di Mapel Lain';
                } else {
                    $s['peringkat'] = $ranking++;
                    $s['keterangan'] = count($hasilMapel[$mapel] ?? []) < $kuotaMapel
                        ? '✅ Terpilih'
                        : '❌ Tidak Terpilih';

                    if ($s['keterangan'] === '✅ Terpilih') {
                        $terpilih[] = $s['id'];
                    }
                }

                $hasilMapel[$mapel][] = $s;
            }
        }

        return view('hasil.index', compact('hasilMapel', 'siswaKurangNilai'));
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

            // Ranking
            usort($data, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

            foreach ($data as $i => &$d) {
                $row['peringkat'] = $i + 1;
                $row['keterangan'] = $i == 0 ? '✅ Terpilih' : '❌ Tidak Terpilih';
            }

            $hasilMapel[$mapel] = $data;
        }

        return $hasilMapel;
    }
}
