@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Data Kriteria</h5>
                <hr>

                <!-- Tabs dan Tombol Add -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="mx-auto d-flex gap-2">
                        @foreach (['ipa', 'ips', 'mtk'] as $mapel)
                            <button type="button"
                                class="btn btn-outline-primary btn-rounded mapel-tab {{ $mapel == 'ipa' ? 'active' : '' }}"
                                data-target="#{{ $mapel }}">
                                {{ strtoupper($mapel) }}
                            </button>
                        @endforeach
                    </div>
                    <div>
                        <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#kriteriaModal"
                            onclick="openAddModal($('.mapel-tab.active').data('target').substring(1))">
                            <i class="fas fa-plus me-2"></i>Tambah Kriteria
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    @foreach (['ipa', 'ips', 'mtk'] as $mapel)
                        <div class="tab-pane {{ $mapel == 'ipa' ? 'active' : '' }}" id="{{ $mapel }}">

                            @php $total = $totalBobot[$mapel] ?? $kriterias->where('mapel', $mapel)->sum('bobot'); @endphp

                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Jumlah total bobot tidak
                                boleh
                                lebih dari 1.
                                Total saat ini:{{ $total }}.
                            </div>
                            <table class="table table-bordered table-striped" id="table-{{ $mapel }}">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Kriteria</th>
                                        <th class="text-center">Bobot</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kriterias->where('mapel', $mapel) as $kriteria)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $kriteria->nama_kriteria }}</td>
                                            <td class="text-center">{{ $kriteria->bobot }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-warning text-white btn-sm border me-1"
                                                    data-bs-toggle="modal" data-bs-target="#kriteriaModal"
                                                    onclick='openEditModal({{ json_encode($kriteria) }})'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger text-white btn-sm border"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-id="{{ $kriteria->id }}">
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

                <!-- Modal Tambah/Edit -->
                <div class="modal fade" id="kriteriaModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-book me-2"></i><span id="modalTitle"></span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="kriteriaForm" method="POST">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" name="id" id="kriteriaId">
                                    <input type="hidden" name="mapel" id="mapel">

                                    <!-- Nama Kriteria -->
                                    <div class="mb-3">
                                        <label class="form-label required">Nama Kriteria</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                            <input type="text" class="form-control" name="nama_kriteria"
                                                id="nama_kriteria" required>
                                        </div>
                                    </div>

                                    <!-- Bobot -->
                                    <div class="mb-3">
                                        <label class="form-label required">Bobot</label>
                                        <div class="input-group mb-1">
                                            <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                            <input type="number" class="form-control" name="bobot" id="bobot"
                                                required step="0.01" min="0">
                                        </div>
                                        <small>Contoh Bobot : 0.5</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Hapus -->
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
                                    Apakah Anda yakin ingin menghapus kriteria ini?
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
            ['ipa', 'ips', 'mtk'].forEach(mapel => {
                $('#table-' + mapel).DataTable();
            });

            $('#deleteModal').on('show.bs.modal', function(event) {
                const id = $(event.relatedTarget).data('id');
                const url = '{{ route('kriteria.destroy', ':id') }}'.replace(':id', id);
                $('#deleteForm').attr('action', url);
            });
        });

        $('.mapel-tab').click(function() {
            const target = $(this).data('target');
            $('.mapel-tab').removeClass('active');
            $(this).addClass('active');
            $('.tab-pane').removeClass('active show');
            $(target).addClass('active show');
        });

        function openAddModal(mapel) {
            const $form = $('#kriteriaForm');
            $form.attr('action', '{{ route('kriteria.store') }}');
            $form.find('input[name="_method"]').remove();

            $('#modalTitle').text('Tambah Kriteria ' + mapel.toUpperCase());
            $('#kriteriaId').val('');
            $('#nama_kriteria').val('');
            $('#bobot').val('');
            $('#mapel').val(mapel);
        }

        function openEditModal(kriteria) {
            const $form = $('#kriteriaForm');
            $form.attr('action', '{{ route('kriteria.update', ':id') }}'.replace(':id', kriteria.id));
            $form.find('input[name="_method"]').remove();
            $form.append('<input type="hidden" name="_method" value="PUT">');

            $('#modalTitle').text('Edit Kriteria ' + kriteria.mapel.toUpperCase());
            $('#kriteriaId').val(kriteria.id);
            $('#nama_kriteria').val(kriteria.nama_kriteria);
            $('#bobot').val(kriteria.bobot);
            $('#mapel').val(kriteria.mapel);
        }
    </script>
@endpush
