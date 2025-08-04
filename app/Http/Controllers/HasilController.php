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
        $defaultKuota = ['mtk' => 2, 'ipa' => 1, 'ips' => 2];
        $kuota = [];

        foreach ($mapels as $m) {
            $kuota[$m] = (int) $request->input("kuota.$m", $defaultKuota[$m]);
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
                        continue 2;
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

    public function simpan(Request $request)
    {
        $mapels = ['mtk', 'ipa', 'ips'];
        $kuota = [];
        foreach ($mapels as $m) {
            $kuota[$m] = (int) ($request->input("kuota.$m", 2));
        }

        $kriterias = [];
        foreach ($mapels as $m) {
            $kriterias[$m] = Kriteria::where('mapel', $m)->get();
        }

        $siswas = Siswa::with('penilaian.kriteria')->get();

        $hasilSementara = [];
        $terpilih = [];
        $hasilMapel = [];

        foreach ($siswas as $siswa) {
            $nilaiPerMapel = [];

            foreach ($mapels as $m) {
                $totalBobot = $kriterias[$m]->sum('bobot');
                if ($totalBobot == 0) continue;

                $nilaiSmart = 0;

                foreach ($kriterias[$m] as $k) {
                    $penilaian = $siswa->penilaian->firstWhere('id_kriteria', $k->id);
                    if (!$penilaian) continue 2;
                    $nilaiSmart += ($penilaian->nilai * $k->bobot) / 100;
                }

                $nilaiPerMapel[$m] = $nilaiSmart;
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

        foreach ($mapels as $m) {
            $siswaMapel = $hasilSementara[$m] ?? [];
            usort($siswaMapel, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

            $kuotaMapel = $kuota[$m] ?? 0;
            $ranking = 1;

            foreach ($siswaMapel as $s) {
                if (in_array($s['id'], $terpilih)) {
                    $s['peringkat'] = '-';
                    $s['keterangan'] = '❌ Sudah Terpilih di Mapel Lain';
                } else {
                    $s['peringkat'] = $ranking++;
                    $s['keterangan'] = count($hasilMapel[$m] ?? []) < $kuotaMapel
                        ? '✅ Terpilih'
                        : '❌ Tidak Terpilih';

                    if ($s['keterangan'] === '✅ Terpilih') {
                        $terpilih[] = $s['id'];
                    }
                }

                if ($s['keterangan'] === '✅ Terpilih' || $s['keterangan'] === '❌ Tidak Terpilih') {
                    $hasilMapel[$m][] = $s;
                }
            }
        }

        // Simpan semua mapel
        foreach ($hasilMapel as $mapel => $daftarSiswa) {
            foreach ($daftarSiswa as $row) {
                // Lewatkan jika bukan mapel yang benar-benar dia terpilih
                if ($row['keterangan'] !== '✅ Terpilih') continue;

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
        }


        return back()->with('success', 'Semua hasil berhasil disimpan!');
    }
}
