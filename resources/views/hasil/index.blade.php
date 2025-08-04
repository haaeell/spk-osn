@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold mb-4">Hasil Akhir SPK (SMART)</h4>

        @if (!empty($siswaKurangNilai))
            <div class="alert alert-danger">
                <strong>Perhatian:</strong> Beberapa siswa tidak memiliki penilaian lengkap dan tidak dihitung:
                <ul class="mb-0 mt-2">
                    @foreach ($siswaKurangNilai as $nama)
                        <li>{{ $nama }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- Tabs --}}
        <div class="d-flex mb-4 gap-2">
            @foreach (['mtk' => 'Matematika', 'ipa' => 'IPA', 'ips' => 'IPS'] as $key => $label)
                <button class="btn btn-outline-primary tab-btn"
                    data-target="#tab-{{ $key }}">{{ $label }}</button>
            @endforeach
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <strong>Input Kuota Peserta per Mapel</strong>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    @foreach (['mtk' => 'Matematika', 'ipa' => 'IPA', 'ips' => 'IPS'] as $key => $label)
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label">{{ $label }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                <input type="number" name="kuota[{{ $key }}]" class="form-control" min="0"
                                    value="{{ request('kuota.' . $key, 2) }}">
                            </div>
                        </div>
                    @endforeach
                    <div class="col-sm-6 col-md-3">
                        <button class="btn btn-success w-100" type="submit">
                            <i class="fas fa-filter"></i> Proses
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tab Contents --}}
        @foreach (['mtk' => 'Matematika', 'ipa' => 'IPA', 'ips' => 'IPS'] as $mapel => $namaMapel)
            <div class="tab-content" id="tab-{{ $mapel }}" style="display: none;">
                <div class="card mb-5">
                    <div class="card-header p-3 text-dark">
                        <strong>Peserta Terpilih OSN {{ strtoupper($namaMapel) }}</strong>
                    </div>
                    <div class="card-body">
                        @if (isset($hasilMapel[$mapel]) && count($hasilMapel[$mapel]) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Nilai Akhir</th>
                                            <th>Peringkat</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hasilMapel[$mapel] as $i => $row)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $row['nama'] }}</td>
                                                <td>{{ $row['kelas'] }}</td>
                                                <td>{{ number_format($row['nilai_akhir'], 4) }}</td>
                                                <td>{{ $row['peringkat'] }}</td>
                                                <td>{{ $row['keterangan'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <form action="{{ route('hasil.simpan', ['mapel' => $mapel]) }}" method="POST"
                                    class="mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Simpan Hasil
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-warning text-center mb-0">
                                Belum ada siswa yang dinilai untuk mapel {{ $namaMapel }}.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.tab-content');

            if (buttons.length > 0) {
                buttons[0].classList.add('active');
                contents[0].style.display = 'block';
            }

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    buttons.forEach(btn => btn.classList.remove('active'));
                    contents.forEach(c => c.style.display = 'none');

                    button.classList.add('active');
                    document.querySelector(button.dataset.target).style.display = 'block';
                });
            });
        });
    </script>
@endpush
