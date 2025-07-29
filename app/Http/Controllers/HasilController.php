<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    public function index()
    {
        $mapels = ['ipa', 'mtk', 'ips'];
        $data = [];

        foreach ($mapels as $mapel) {
            $siswas = Siswa::with(['penilaians' => function ($q) use ($mapel) {
                $q->where('mapel', $mapel);
            }])->get();

            $kriterias = Kriteria::where('mapel', $mapel)->get();
            $data[$mapel] = compact('siswas', 'kriterias');
        }

        return view('hasil.index', compact('data'));
    }
}
