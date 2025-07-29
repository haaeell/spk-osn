@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Data Siswa</h5>
                <hr>

                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#siswaModal"
                        onclick="openAddSiswa()">
                        <i class="fas fa-plus me-2"></i>Tambah Siswa
                    </button>
                </div>

                <table class="table table-bordered table-striped" id="siswaTable">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">NIS</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Jenis Kelamin</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswas as $siswa)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $siswa->nis }}</td>
                                <td class="text-center">{{ $siswa->nama }}</td>
                                <td class="text-center">{{ $siswa->kelas }}</td>
                                <td class="text-center">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-warning text-white btn-sm border me-1" data-bs-toggle="modal"
                                        data-bs-target="#siswaModal" onclick='openEditSiswa(@json($siswa))'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger text-white btn-sm border"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $siswa->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Modal Tambah/Edit --}}
                <div class="modal fade" id="siswaModal" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" id="siswaForm">
                            @csrf
                            <input type="hidden" name="id" id="siswaId">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fas fa-user-graduate me-2"></i><span
                                            id="siswaModalTitle"></span></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">NIS</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input type="text" class="form-control" name="nis" id="nis"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" name="nama" id="nama"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kelas</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-school"></i></span>
                                            <input type="text" class="form-control" name="kelas" id="kelas"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                            <select class="form-select" name="jenis_kelamin" id="jenis_kelamin" required>
                                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-primary" type="submit"><i
                                            class="fas fa-save me-2"></i>Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal Delete --}}
                <div class="modal fade" id="deleteModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" id="deleteForm">
                            @csrf
                            @method('DELETE')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus data siswa ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#siswaTable').DataTable();

            $('#deleteModal').on('show.bs.modal', function(event) {
                const id = $(event.relatedTarget).data('id');
                $('#deleteForm').attr('action', '{{ route('siswa.destroy', ':id') }}'.replace(':id', id));
            });
        });

        function openAddSiswa() {
            $('#siswaForm').attr('action', '{{ route('siswa.store') }}');
            $('#siswaForm').find('input[name="_method"]').remove();
            $('#siswaModalTitle').text('Tambah Siswa');
            $('#siswaId').val('');
            $('#nis').val('');
            $('#nama').val('');
            $('#kelas').val('');
            $('#jenis_kelamin').val('');
        }

        function openEditSiswa(siswa) {
            $('#siswaForm').attr('action', '{{ route('siswa.update', ':id') }}'.replace(':id', siswa.id));
            $('#siswaForm').find('input[name="_method"]').remove();
            $('#siswaForm').append('<input type="hidden" name="_method" value="PUT">');

            $('#siswaModalTitle').text('Edit Siswa');
            $('#siswaId').val(siswa.id);
            $('#nis').val(siswa.nis);
            $('#nama').val(siswa.nama);
            $('#kelas').val(siswa.kelas);
            $('#jenis_kelamin').val(siswa.jenis_kelamin);
        }
    </script>
@endpush
