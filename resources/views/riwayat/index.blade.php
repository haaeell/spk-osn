@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold mb-4">Riwayat Perhitungan</h4>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Mata Pelajaran</th>
                                <th>Tanggal Perhitungan</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayat as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ strtoupper($row->mapel) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                                    <td>
                                        <!-- Tombol Detail -->
                                        <button class="btn btn-primary btn-sm text-white" data-bs-toggle="modal"
                                            data-bs-target="#modalDetail{{ $i }}">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </button>

                                        <!-- Tombol PDF -->
                                        <a href="{{ route('riwayat.pdf', $row->tanggal) }}" target="_blank"
                                            class="btn btn-danger btn-sm text-white">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </a>

                                        <!-- Modal Detail -->
                                        <div class="modal fade" id="modalDetail{{ $i }}" tabindex="-1"
                                            aria-labelledby="modalLabel{{ $i }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel{{ $i }}">
                                                            Detail Perhitungan - {{ $row->tanggal }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $data = \App\Models\Hasil::whereDate(
                                                                'created_at',
                                                                $row->tanggal,
                                                            )
                                                                ->where('mapel', $row->mapel)
                                                                ->with('siswa')
                                                                ->get();
                                                        @endphp

                                                        @if ($data->count() > 0)
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered text-center">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Nama</th>
                                                                            <th>Kelas</th>
                                                                            <th>Nilai Akhir</th>
                                                                            <th>Keterangan</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($data as $j => $item)
                                                                            @php $hasil = json_decode($item->hasil) @endphp
                                                                            <tr>
                                                                                <td>{{ $j + 1 }}</td>
                                                                                <td>{{ $hasil->nama ?? '-' }}</td>
                                                                                <td>{{ $hasil->kelas ?? '-' }}</td>
                                                                                <td>{{ number_format($item->nilai_akhir, 4) }}
                                                                                </td>
                                                                                <td>{{ $hasil->keterangan ?? '-' }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <div class="alert alert-warning text-center">
                                                                Data tidak ditemukan.
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i> Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Belum ada riwayat perhitungan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
