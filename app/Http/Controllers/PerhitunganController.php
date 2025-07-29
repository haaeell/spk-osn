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
        $mapel = Auth::user()->kategori_mapel ?? 'mtk';
        $penilaians = Penilaian::where('mapel', $mapel)->get();

        if ($penilaians->isEmpty()) {
            return view('perhitungan.index', [
                'message' => 'Belum ada siswa yang dinilai.',
                'data' => null
            ]);
        }

        $kriterias = Kriteria::where('mapel', $mapel)->get();
        $siswas = Siswa::with(['penilaian' => function ($q) use ($mapel) {
            $q->where('mapel', $mapel);
        }])->get();

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

        return view('perhitungan.index', [
            'message' => null,
            'mapel' => $mapel,
            'siswas' => $siswas,
            'kriterias' => $kriterias,
            'hasil' => $hasil,
        ]);
    }
}
