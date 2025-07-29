<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayat = Hasil::select(DB::raw('DATE(created_at) as tanggal'))
            ->groupBy('tanggal')
            ->orderByDesc('tanggal')
            ->get();

        return view('riwayat.index', compact('riwayat'));
    }

    public function detail($tanggal)
    {
        $data = Hasil::whereDate('created_at', $tanggal)
            ->with('siswa')
            ->get();

        return view('hasil.detail', compact('data', 'tanggal'));
    }

    public function cetakPdf($tanggal)
    {
        $data = Hasil::whereDate('created_at', $tanggal)
            ->with('siswa')
            ->get();

        $pdf = Pdf::loadView('hasil.pdf', compact('data', 'tanggal'));
        return $pdf->download("hasil_perhitungan_$tanggal.pdf");
    }
}
