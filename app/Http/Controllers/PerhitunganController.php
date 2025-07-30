<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerhitunganController extends Controller
{
    public function index()
    {
        $mapels = ['ipa', 'ips', 'mtk'];
        $semua_hasil = [];

        foreach ($mapels as $mapel) {
            $penilaians = Penilaian::where('mapel', $mapel)->get();

            if ($penilaians->isEmpty()) {
                $semua_hasil[$mapel] = [
                    'message' => 'Belum ada siswa yang dinilai.',
                    'siswas' => [],
                    'kriterias' => [],
                    'hasil' => collect(),
                ];
                continue;
            }

            $kriterias = Kriteria::where('mapel', $mapel)->get();
            $siswas = Siswa::with([
                'penilaian' => function ($q) use ($mapel) {
                    $q->where('mapel', $mapel);
                }
            ])->get();

            $hasil = collect();

            foreach ($siswas as $siswa) {
                $total = 0;
                foreach ($kriterias as $kriteria) {
                    $nilai = $siswa->penilaian->where('id_kriteria', $kriteria->id)->first()->nilai ?? 0;
                    $total += ($nilai / 100) * $kriteria->bobot;
                }

                $hasil->push([
                    'siswa' => $siswa,
                    'skor' => round($total, 4),
                ]);
            }

            $semua_hasil[$mapel] = [
                'message' => null,
                'siswas' => $siswas,
                'kriterias' => $kriterias,
                'hasil' => $hasil,
            ];
        }

        return view('perhitungan.index', [
            'semua_hasil' => $semua_hasil,
        ]);
    }
}
