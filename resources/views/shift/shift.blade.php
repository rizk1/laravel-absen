@extends('layout.app-layout')
@section('title-page', 'Shift - ')
@section('title-content', 'Shift')

@section('css')
<link rel="stylesheet" href="{{asset('assets/modules/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
@endsection

@section('content')
<h2 class="section-title">Data Shift</h2>
<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
        <h4>Data Shift</h4>
        <div class="card-header-action">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-add-shift">Tambah Shift</button>
        </div>
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="tabelShift">
            <thead>                                 
                <tr>
                    <th>No</th>
                    <th>Shift</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            </table>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade" tabindex="-1" role="dialog" id="modal-map">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Peta Absen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="map-data" class="map-responsive"></div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<!-- Add Shift Modal -->
<div class="modal fade" id="modal-add-shift" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-add-shift">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Shift</label>
                        <input type="text" class="form-control" name="shift" required>
                    </div>
                    <div class="form-group">
                        <label>Mulai</label>
                        <input type="time" class="form-control" name="mulai" required>
                    </div>
                    <div class="form-group">
                        <label>Selesai</label>
                        <input type="time" class="form-control" name="selesai" required>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Shift Modal -->
<div class="modal fade" id="modal-edit-shift" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit-shift">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-shift-id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Shift</label>
                        <input type="text" class="form-control" name="shift" id="edit-shift-name" required>
                    </div>
                    <div class="form-group">
                        <label>Mulai</label>
                        <input type="time" class="form-control" name="mulai" id="edit-shift-start" required>
                    </div>
                    <div class="form-group">
                        <label>Selesai</label>
                        <input type="time" class="form-control" name="selesai" id="edit-shift-end" required>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Shift Modal -->
<div class="modal fade" id="modal-delete-shift" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus shift ini?</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-shift">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/modules/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/modules/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/js/page/modules-datatables.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://momentjs.com/downloads/moment.js"></script>
<script>
$(document).ready(function() {
    var table = $('#tabelShift').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('shift.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'shift', name: 'shift'},
            {data: 'mulai', name: 'mulai'},
            {data: 'selesai', name: 'selesai'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    // Fungsi validasi
    function validateShiftForm(formData) {
        var errors = [];
        if (!formData.shift.trim()) {
            errors.push('Nama Shift harus diisi');
        }
        if (!formData.mulai) {
            errors.push('Waktu Mulai harus diisi');
        }
        if (!formData.selesai) {
            errors.push('Waktu Selesai harus diisi');
        }
        if (formData.mulai && formData.selesai && formData.mulai >= formData.selesai) {
            errors.push('Waktu Selesai harus lebih besar dari Waktu Mulai');
        }
        return errors;
    }

    // Add Shift
    $('#form-add-shift').submit(function(e) {
        e.preventDefault();
        var formData = {
            shift: $('input[name="shift"]').val(),
            mulai: $('input[name="mulai"]').val(),
            selesai: $('input[name="selesai"]').val()
        };
        
        var errors = validateShiftForm(formData);
        if (errors.length > 0) {
            Swal.fire('Error!', errors.join('<br>'), 'error');
            return;
        }

        $.ajax({
            url: "{{ route('shift.store') }}",
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#modal-add-shift').modal('hide');
                table.ajax.reload();
                Swal.fire('Berhasil!', 'Shift baru telah ditambahkan.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Terjadi kesalahan saat menambahkan shift.', 'error');
            }
        });
    });

    // Edit Shift
    $(document).on('click', '.edit-shift', function() {
        var id = $(this).data('id');
        $.get("{{ url('shift') }}/" + id + "/edit", function(data) {
            $('#edit-shift-id').val(data.id);
            $('#edit-shift-name').val(data.shift);
            $('#edit-shift-start').val(data.mulai);
            $('#edit-shift-end').val(data.selesai);
            $('#modal-edit-shift').modal('show');
        });
    });

    $('#form-edit-shift').submit(function(e) {
        e.preventDefault();
        var id = $('#edit-shift-id').val();
        var formData = {
            shift: $('#edit-shift-name').val(),
            mulai: $('#edit-shift-start').val(),
            selesai: $('#edit-shift-end').val()
        };
        
        var errors = validateShiftForm(formData);
        if (errors.length > 0) {
            Swal.fire('Error!', errors.join('<br>'), 'error');
            return;
        }

        $.ajax({
            url: "{{ url('shift') }}/" + id,
            method: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#modal-edit-shift').modal('hide');
                table.ajax.reload();
                Swal.fire('Berhasil!', 'Shift telah diperbarui.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Terjadi kesalahan saat memperbarui shift.', 'error');
            }
        });
    });

    // Delete Shift
    var deleteId;
    $(document).on('click', '.delete-shift', function() {
        deleteId = $(this).data('id');
        $('#modal-delete-shift').modal('show');
    });

    $('#confirm-delete-shift').click(function() {
        $.ajax({
            url: "{{ url('shift') }}/" + deleteId,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#modal-delete-shift').modal('hide');
                table.ajax.reload();
                Swal.fire('Berhasil!', 'Shift telah dihapus.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus shift.', 'error');
            }
        });
    });

});
</script>
@endsection