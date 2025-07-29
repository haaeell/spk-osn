<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $mapels = ['ipa', 'ips', 'mtk'];
        $kriterias = Kriteria::all();
        $totalBobot = [];

        foreach ($mapels as $mapel) {
            $totalBobot[$mapel] = $kriterias->where('mapel', $mapel)->sum('bobot');
        }

        return view('kriteria.index', compact('kriterias', 'totalBobot'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_kriteria' => 'required',
            'bobot' => 'required|numeric|min:0|max:1',
            'mapel' => 'required|in:ipa,mtk,ips',
        ], [
            'bobot.required' => 'Bobot harus diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka.',
            'bobot.min' => 'Bobot minimal adalah 0.',
            'bobot.max' => 'Bobot maksimal adalah 1.',
        ]);

        $totalBobot = Kriteria::where('mapel', $request->mapel)
            ->when($request->id, fn($q) => $q->where('id', '!=', $request->id))
            ->sum('bobot');

        if ($totalBobot + $request->bobot > 1) {
            return back()->with('error', 'Total bobot tidak boleh lebih dari 1.');
        }

        Kriteria::create([
            'nama_kriteria' => $request->nama_kriteria,
            'bobot' => $request->bobot,
            'mapel' => $request->mapel
        ]);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kriteria' => 'required',
            'bobot' => 'required|numeric|min:0|max:1',
            'mapel' => 'required|in:ipa,mtk,ips',
        ]);

        $kriteria = Kriteria::findOrFail($id);

        $totalBobot = Kriteria::where('mapel', $request->mapel)
            ->when($request->id, fn($q) => $q->where('id', '!=', $request->id))
            ->sum('bobot');

        if ($totalBobot + $request->bobot > 1) {
            return back()->with('error', 'Total bobot tidak boleh lebih dari 1.');
        }

        $kriteria->update([
            'nama_kriteria' => $request->nama_kriteria,
            'bobot' => $request->bobot,
            'mapel' => $request->mapel
        ]);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();
        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}
