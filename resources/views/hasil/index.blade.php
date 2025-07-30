@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold mb-4">Hasil Akhir SPK (SMART)</h4>

        {{-- Tabs --}}
        <div class="d-flex mb-4 gap-2">
            @foreach ($hasilMapel as $key => $rows)
                <button class="btn btn-outline-primary tab-btn"
                    data-target="#tab-{{ $key }}">{{ strtoupper($key) }}</button>
            @endforeach
        </div>

        {{-- Tab Contents --}}
        @foreach ($hasilMapel as $mapel => $data)
            <div class="tab-content" id="tab-{{ $mapel }}" style="display: none;">
                <div class="card mb-5">
                    <div class="card-header p-3 text-dark">
                        <strong>Peserta Terpilih OSN {{ strtoupper($mapel) }}</strong>
                    </div>
                    <div class="card-body">
                        @if (count($data) > 0)
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
                                        @foreach ($data as $i => $row)
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
                                    class="mb-3 mt-3 ">
                                    @csrf
                                    <button type="submit" class="btn btn-success text-white">
                                        <i class="fas fa-save"></i> Simpan Hasil
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-warning text-center mb-0">
                                Belum ada siswa yang dinilai untuk mapel {{ strtoupper($mapel) }}.
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
