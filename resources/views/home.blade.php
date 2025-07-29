@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h5 class="fw-bold mb-3">Dashboard</h5>
        <hr>

        <div class="text-center p-4 mb-4 rounded" style="background-color: #dee8f8">
            <h3 class="fw-semibold mb-0">
                Selamat Datang di Sistem Pendukung Keputusan Pemilihan <br>
                Peserta Olimpiade Sains Nasional
            </h3>
        </div>

        <div class="row g-4">
            <!-- Card: Pengguna -->
            <div class="col-md-4">
                <a href="{{ route('users.index') }}" class="text-decoration-none">
                    <div class="border rounded shadow-sm overflow-hidden">
                        <div class="bg-success bg-opacity-75 text-white text-center p-3">
                            <div class="fs-2 mb-2">
                                <i class="fas fa-database"></i> <i class="fas fa-user"></i>
                            </div>
                            <h6 class="fw-semibold mb-0">Jumlah Data Pengguna</h6>
                            <div class="fs-3 fw-bold">3</div>
                        </div>
                        <div class="bg-light d-flex justify-content-between align-items-center px-3 py-2">
                            <span class="text-muted">Lihat Detail</span>
                            <div class="bg-white rounded-circle p-2">
                                <i class="fas fa-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card: Siswa -->
            <div class="col-md-4">
                <a href="{{ route('siswa.index') }}" class="text-decoration-none">
                    <div class="border rounded shadow-sm overflow-hidden">
                        <div class="text-white text-center p-3" style="background-color: #20c997;">
                            <div class="fs-2 mb-2">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h6 class="fw-semibold mb-0">Jumlah Data Siswa</h6>
                            <div class="fs-3 fw-bold">100</div>
                        </div>
                        <div class="bg-light d-flex justify-content-between align-items-center px-3 py-2">
                            <span class="text-muted">Lihat Detail</span>
                            <div class="bg-white rounded-circle p-2">
                                <i class="fas fa-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card: Kriteria -->
            <div class="col-md-4">
                <a href="{{ route('kriteria.index') }}" class="text-decoration-none">
                    <div class="border rounded shadow-sm overflow-hidden">
                        <div class="bg-primary bg-opacity-75 text-white text-center p-3">
                            <div class="fs-2 mb-2">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <h6 class="fw-semibold mb-0">Jumlah Data Kriteria</h6>
                            <div class="fs-3 fw-bold">5</div>
                        </div>
                        <div class="bg-light d-flex justify-content-between align-items-center px-3 py-2">
                            <span class="text-muted">Lihat Detail</span>
                            <div class="bg-white rounded-circle p-2">
                                <i class="fas fa-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
