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
        $kategori = Auth::user()->kategori_mapel;
        $penilaians = Penilaian::where('mapel', $kategori)->get();
        return view('penilaian.index', compact('penilaians'));
    }

    public function create()
    {
        $kategori = Auth::user()->kategori_mapel;
        $siswas = Siswa::all();
        $kriterias = Kriteria::where('mapel', $kategori)->get();

        return view('penilaian.create', compact('siswas', 'kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'nilai' => 'required|array',
        ]);

        foreach ($request->nilai as $kriteria_id => $nilai) {
            Penilaian::create([
                'siswa_id' => $request->siswa_id,
                'kriteria_id' => $kriteria_id,
                'nilai' => $nilai,
                'mapel' => Auth::user()->kategori_mapel,
                'penilai_id' => Auth::id(),
            ]);
        }

        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil disimpan.');
    }
}
