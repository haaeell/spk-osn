@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Data Penilaian</h5>
                <hr>

                @foreach ($mapels as $mapel)
                    <h6 class="fw-bold mt-4">{{ strtoupper($mapel) }}</h6>

                    <form action="{{ route('penilaian.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="mapel" value="{{ $mapel }}">

                        <table class="table table-bordered table-striped" id="table-{{ $mapel }}">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Siswa</th>
                                    <th class="text-center">Kelas</th>
                                    <th class="text-center">NIS</th>
                                    @foreach ($kriterias->where('mapel', $mapel) as $kriteria)
                                        <th class="text-center">{{ $kriteria->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswas as $siswa)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $siswa->nama }}</td>
                                        <td class="text-center">{{ $siswa->kelas }}</td>
                                        <td class="text-center">{{ $siswa->nis }}</td>
                                        @foreach ($kriterias->where('mapel', $mapel) as $kriteria)
                                            <td class="text-center">
                                                <input type="number"
                                                    name="nilai[{{ $siswa->id }}][{{ $kriteria->id }}]"
                                                    class="form-control text-center"
                                                    value="{{ $siswa->penilaian->where('id_kriteria', $kriteria->id)->first()->nilai ?? '' }}"
                                                    min="0" max="100">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-end mt-2">
                            <button type="submit" class="btn btn-success btn-rounded text-white">
                                <i class="fas fa-save"></i> Simpan {{ strtoupper($mapel) }}
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @foreach ($mapels as $mapel)
                $('#table-{{ $mapel }}').DataTable({
                    paging: false,
                    searching: false,
                    ordering: false,
                    info: false
                });
            @endforeach
        });
    </script>
@endpush
