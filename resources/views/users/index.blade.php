@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Data Pengguna</h5>
                <hr>

                <!-- Tabs dan Tombol Add -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="mx-auto d-flex gap-2">
                        @foreach (['admin', 'penilai', 'kepala_sekolah'] as $role)
                            <button type="button"
                                class="btn btn-outline-primary btn-rounded role-tab {{ $role == 'admin' ? 'active' : '' }}"
                                data-target="#{{ $role }}">
                                {{ ucfirst(str_replace('_', ' ', $role)) }}
                            </button>
                        @endforeach
                    </div>
                    <div>
                        <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#userModal"
                            onclick="openAddModal($('.role-tab.active').data('target').substring(1))">
                            <i class="fas fa-plus me-2"></i>Tambah Pengguna
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    @foreach (['admin', 'penilai', 'kepala_sekolah'] as $role)
                        <div class="tab-pane {{ $role == 'admin' ? 'active' : '' }}" id="{{ $role }}">
                            <table class="table table-bordered table-striped" id="table-{{ $role }}">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Email</th>
                                        @if ($role == 'penilai')
                                            <th class="text-center">Kategori Mapel</th>
                                        @endif
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users->where('role', $role) as $user)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $user->name }}</td>
                                            <td class="text-center">{{ $user->email }}</td>
                                            @if ($role == 'penilai')
                                                <td class="text-center">{{ $user->kategori_mapel }}</td>
                                            @endif
                                            <td class="text-center">
                                                <button class="btn btn-warning text-white btn-sm border me-1"
                                                    data-bs-toggle="modal" data-bs-target="#userModal"
                                                    onclick='openEditModal({{ json_encode($user) }})'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger text-white btn-sm border"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-id="{{ $user->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>

                <!-- Modal -->
                <div class="modal fade" id="userModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-user me-2"></i><span id="modalTitle"></span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="userForm" method="POST">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" id="userId" name="id">
                                    <input type="hidden" id="role" name="role">

                                    <!-- Nama -->
                                    <div class="mb-3">
                                        <label class="form-label required">Nama</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" name="nama" id="nama"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label class="form-label required">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" name="email" id="email"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" name="password" id="password">
                                        </div>
                                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah
                                            password</small>
                                    </div>

                                    <!-- Kategori Mapel (Penilai Only) -->
                                    <div class="mb-3" id="kategoriMapelContainer" style="display: none;">
                                        <label class="form-label">Kategori Mapel</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                                            <select class="form-select" name="kategori_mapel" id="kategori_mapel">
                                                <option value="" disabled selected>Pilih kategori</option>
                                                <option value="ipa">IPA</option>
                                                <option value="ips">IPS</option>
                                                <option value="mtk">MTK</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


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
                                    Apakah Anda yakin ingin menghapus pengguna ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </button>
                                    <button type="submit" class="btn btn-danger text-white">
                                        <i class="fas fa-trash me-2"></i>Hapus
                                    </button>
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
            ['admin', 'penilai', 'kepala_sekolah'].forEach(role => {
                $('#table-' + role).DataTable();
            });

            $('#deleteModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const url = '{{ route('users.destroy', ':id') }}'.replace(':id', id);
                $('#deleteForm').attr('action', url);
            });
        });

        $('.role-tab').click(function() {
            let target = $(this).data('target');
            $('.role-tab').removeClass('active');
            $(this).addClass('active');
            $('.tab-pane').removeClass('active show');
            $(target).addClass('active show');
        });

        function openAddModal(role) {
            const $form = $('#userForm');
            $form.attr('action', '{{ route('users.store') }}');
            $form.find('input[name="_method"]').remove();
            $('#modalTitle').text('Tambah ' + capitalize(role));
            $('#userId').val('');
            $('#nama').val('');
            $('#email').val('');
            $('#password').val('').prop('required', true);
            $('#role').val(role);
            $('#kategoriMapelContainer').toggle(role === 'penilai');
            $('#kategori_mapel').val('');
        }

        function openEditModal(user) {
            const $form = $('#userForm');
            $form.attr('action', '{{ route('users.update', ':id') }}'.replace(':id', user.id));
            $form.find('input[name="_method"]').remove();
            $form.append('<input type="hidden" name="_method" value="PUT">');

            $('#modalTitle').text('Edit ' + capitalize(user.role));
            $('#userId').val(user.id);
            $('#nama').val(user.name);
            $('#email').val(user.email);
            $('#password').val('').prop('required', false);
            $('#role').val(user.role);
            $('#kategoriMapelContainer').toggle(user.role === 'penilai');
            $('#kategori_mapel').val(user.kategori_mapel || '');
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    </script>
@endpush
