@extends('layout.app-layout')
@section('title-page', 'Jabatan - ')
@section('title-content', 'Jabatan')

@section('css')
<link rel="stylesheet" href="{{asset('assets/modules/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
@endsection

@section('content')
<h2 class="section-title">Data Jabatan</h2>
<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
        <h4>Data Jabatan</h4>
        <div class="card-header-action">
            <button class="btn btn-primary btn-add-jabatan">Tambah Jabatan</button>
        </div>
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="tabelJabatan">
            <thead>                                 
                <tr>
                    <th>No</th>
                    <th>Jabatan</th>
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
<!-- Add/Edit Jabatan Modal -->
<div class="modal fade" id="modal-jabatan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Edit Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-jabatan">
                @csrf
                <input type="hidden" name="id" id="jabatan-id">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" class="form-control form-control-sm" id="jabatan-nama">
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

<!-- Delete Jabatan Modal -->
<div class="modal fade" id="modal-delete-jabatan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus jabatan ini?</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-jabatan">Hapus</button>
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
<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#tabelJabatan').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('jabatan.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'jabatan', name: 'jabatan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    // Function to reset modal form
    function resetModalForm() {
        $('#form-jabatan')[0].reset();
        $('#jabatan-id').val('');
        $('#modal-title').text('Tambah Jabatan');
    }

    // Add Jabatan button click
    $('.btn-add-jabatan').click(function() {
        resetModalForm();
        $('#modal-jabatan').modal('show');
    });

    // Edit Jabatan button click
    $(document).on('click', '.edit-jabatan', function() {
        var id = $(this).data('id');
        $('#modal-title').text('Edit Jabatan');
        
        $.get("{{ url('jabatan') }}/" + id + "/edit", function(data) {
            $('#jabatan-id').val(data.id);
            $('#jabatan-nama').val(data.jabatan);
            $('#modal-jabatan').modal('show');
        });
    });

    // Form submission
    $('#form-jabatan').submit(function(e) {
        e.preventDefault();
        
        // Menggunakan serializeArray untuk mengambil data form
        var formArray = $(this).serializeArray();
        var formData = {};
        
        $.each(formArray, function(i, field){
            formData[field.name] = field.value;
        });
        
        // Log form data
        console.log('Form Data:', formData);

        var jabatanId = $('#jabatan-id').val();
        var url = jabatanId ? "{{ url('jabatan') }}/" + jabatanId : "{{ route('jabatan.store') }}";
        var method = jabatanId ? 'PUT' : 'POST';

        // Log request details
        console.log('Request URL:', url);
        console.log('Request Method:', method);

        $.ajax({
            url: url,
            method: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Success Response:', response);
                $('#modal-jabatan').modal('hide');
                table.ajax.reload();
                Swal.fire('Berhasil!', 'Jabatan telah ' + (jabatanId ? 'diperbarui.' : 'ditambahkan.'), 'success');
            },
            error: function(xhr, status, error) {
                console.log('Error Status:', status);
                console.log('Error:', error);
                console.log('Error Response:', xhr.responseText);
                Swal.fire('Error!', 'Terjadi kesalahan saat memproses data jabatan.', 'error');
            }
        });
    });

    // Delete Jabatan
    var deleteId;
    $(document).on('click', '.delete-jabatan', function() {
        deleteId = $(this).data('id');
        $('#modal-delete-jabatan').modal('show');
    });

    $('#confirm-delete-jabatan').click(function() {
        $.ajax({
            url: "{{ url('jabatan') }}/" + deleteId,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#modal-delete-jabatan').modal('hide');
                table.ajax.reload();
                Swal.fire('Berhasil!', 'Jabatan telah dihapus.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus jabatan.', 'error');
            }
        });
    });

    // Event handler when modal is closed
    $('#modal-jabatan').on('hidden.bs.modal', function () {
        resetModalForm();
    });

});
</script>
@endsection