<?php

namespace App\Http\Controllers;

use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::all();
        return view('siswa.index', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa',
            'nama' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
            return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport, 'siswa_template.xlsx');
    }
}
