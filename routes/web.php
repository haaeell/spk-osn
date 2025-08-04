<?php

use App\Http\Controllers\HasilController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('role:admin')->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{tanggal}/detail', [RiwayatController::class, 'detail'])->name('riwayat.detail');
    Route::get('/riwayat/{tanggal}/pdf', [RiwayatController::class, 'cetakPdf'])->name('riwayat.pdf');
    Route::get('hasil', [HasilController::class, 'index'])->name('hasil.index');
    Route::post('/hasil/simpan', [HasilController::class, 'simpan'])->name('hasil.simpan');
    Route::get('/hasil/{id}/detail', [HasilController::class, 'detail'])->name('hasil.detail');
    Route::get('/hasil/{id}/pdf', [HasilController::class, 'cetakPdf'])->name('hasil.pdf');

    Route::resource('siswa', SiswaController::class);
    Route::resource('kriteria', KriteriaController::class);

    Route::get('penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
});

Route::middleware('role:penilai')->group(function () {
    Route::get('penilaian/create', [PenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
});

Route::middleware('role:kepala_sekolah')->group(function () {});
