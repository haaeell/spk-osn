<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\Siswa;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $jumlahUser = User::count();
        $jumlahSiswa = Siswa::count();
        $jumlahKriteria = Kriteria::count();
        return view('home', compact('jumlahUser','jumlahKriteria','jumlahSiswa'));
    }
}
