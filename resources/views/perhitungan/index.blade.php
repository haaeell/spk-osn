@extends('layouts.app')

@section('content')
    <div class="py-4">
        @foreach ($semua_hasil as $mapel => $data)
            <div class="card mb-5">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Perhitungan Metode SMART - {{ strtoupper($mapel) }}</h5>
                    <hr>

                    @if ($data['message'])
                        <div class="alert alert-warning text-center">
                            {{ $data['message'] }}
                        </div>
                    @else
                        @php
                            $kriterias = $data['kriterias'];
                            $siswas = $data['siswas'];
                            $hasil = $data['hasil'];
                        @endphp

                        <h6 class="fw-bold">1. Nilai Asli</h6>
                        <table class="table table-bordered mb-4">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">Nama</th>
                                    @foreach ($kriterias as $k)
                                        <th class="text-center">{{ $k->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswas as $siswa)
                                    <tr>
                                        <td class="text-center">{{ $siswa->nama }}</td>
                                        @foreach ($kriterias as $k)
                                            <td class="text-center">
                                                {{ $siswa->penilaian->where('id_kriteria', $k->id)->first()->nilai ?? '-' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h6 class="fw-bold">2. Normalisasi Nilai (dibagi 100)</h6>
                        <table class="table table-bordered mb-4">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">Nama</th>
                                    @foreach ($kriterias as $k)
                                        <th class="text-center">{{ $k->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswas as $siswa)
                                    <tr>
                                        <td class="text-center">{{ $siswa->nama }}</td>
                                        @foreach ($kriterias as $k)
                                            @php
                                                $nilai = $siswa->penilaian->where('id_kriteria', $k->id)->first()->nilai ?? 0;
                                                $normal = $nilai / 100;
                                            @endphp
                                            <td class="text-center">{{ number_format($normal, 2) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h6 class="fw-bold">3. Normalisasi × Bobot Kriteria</h6>
                        <table class="table table-bordered mb-4">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">Nama</th>
                                    @foreach ($kriterias as $k)
                                        <th class="text-center">{{ $k->nama_kriteria }}<br>
                                            <small>(Bobot: {{ $k->bobot }})</small>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswas as $siswa)
                                    <tr>
                                        <td class="text-center">{{ $siswa->nama }}</td>
                                        @foreach ($kriterias as $k)
                                            @php
                                                $nilai = $siswa->penilaian->where('id_kriteria', $k->id)->first()->nilai ?? 0;
                                                $normal = $nilai / 100;
                                                $terbobot = $normal * $k->bobot;
                                            @endphp
                                            <td class="text-center">{{ number_format($terbobot, 4) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h6 class="fw-bold">4. Skor Akhir</h6>
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">Ranking</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Kelas</th>
                                    <th class="text-center">NIS</th>
                                    <th class="text-center">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasil->sortByDesc('skor')->values() as $index => $row)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $row['siswa']->nama }}</td>
                                        <td class="text-center">{{ $row['siswa']->kelas }}</td>
                                        <td class="text-center">{{ $row['siswa']->nis }}</td>
                                        <td class="text-center fw-bold">{{ number_format($row['skor'], 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
