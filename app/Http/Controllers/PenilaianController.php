<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index()
    {
        $mapels = Kriteria::select('mapel')->distinct()->pluck('mapel');
        $siswas = Siswa::with('penilaian')->get();
        $kriterias = Kriteria::all();

        return view('penilaian.index', compact('mapels', 'siswas', 'kriterias'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'mapel' => 'required|string',
            'nilai' => 'required|array',
        ]);

        $mapel = $request->input('mapel');
        $nilaiData = $request->input('nilai');
        $penilaiId = Auth::id();

        foreach ($nilaiData as $siswa_id => $kriterias) {
            foreach ($kriterias as $kriteria_id => $nilai) {
                Penilaian::updateOrCreate(
                    [
                        'id_siswa' => $siswa_id,
                        'id_kriteria' => $kriteria_id,
                        'mapel' => $mapel,
                        'id_penilai' => $penilaiId,
                    ],
                    [
                        'nilai' => $nilai
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Nilai ' . strtoupper($mapel) . ' berhasil disimpan.');
    }
}
