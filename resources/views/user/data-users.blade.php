@extends('layout.app-layout')
@section('title-page', 'Users - ')
@section('title-content', 'Users')

@section('css')
<link rel="stylesheet" href="{{asset('assets/modules/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
@endsection

@section('content')
<h2 class="section-title">Data Users</h2>
<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
        <h4>Data Users</h4>
        <div class="card-header-action">
            <button class="btn btn-primary btn-add-user">Tambah User</button>
        </div>
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="tabelUsers">
            <thead>                                 
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
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
<!-- Add/Edit User Modal -->
<div class="modal fade" id="modal-user" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-user" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="user-id">
                <div class="card-body">
                    <div class="form-group mb-2">
                        <label>Foto Karyawan</label>
                        <input name="photo" type="file" class="dropify" data-height="100" data-allowed-file-extensions="png jpg jpeg" 
                               data-default-file="" id="user-photo" />
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama Karyawan</label>
                        <input type="text" name="name" class="form-control form-control-sm" id="user-name">
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" id="user-email">
                    </div>
                    <div class="form-group mb-3">
                        <label>Jabatan</label>
                        <select name="jabatan" class="form-control form-control-sm" id="user-jabatan">
                            @foreach ($jabatan as $item)
                                <option value="{{ $item->id }}">{{ $item->jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" id="user-password">
                        <small class="form-text text-muted" id="password-help" style="display: none;">Leave blank if you don't want to change the password.</small>
                    </div>
                    <div class="form-group mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="user-password-confirm">
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

<!-- Delete User Modal -->
<div class="modal fade" id="modal-delete-user" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user ini?</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-user">Hapus</button>
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
    var table = $('#tabelUsers').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'jabatan.jabatan', name: 'jabatan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('.dropify').dropify();

    // Function to reset modal form
    function resetModalForm() {
        $('#form-user')[0].reset();
        $('#user-id').val('');
        $('#user-photo').attr('data-default-file', '').dropify('resetPreview');
        $('#modal-title').text('Tambah User');
        $('#password-help').hide();
        
        // Reset Dropify
        var drEvent = $('#user-photo').dropify();
        drEvent = drEvent.data('dropify');
        drEvent.resetPreview();
        drEvent.clearElement();
        drEvent.settings.defaultFile = '';
        drEvent.destroy();
        drEvent.init();
    }

    // Add User button click
    $('.btn-add-user').click(function() {
        resetModalForm();
        $('#modal-user').modal('show');
    });

    // Edit User button click
    $(document).on('click', '.edit-user', function() {
        var id = $(this).data('id');
        $('#modal-title').text('Edit User');
        $('#password-help').show();
        
        $.get("{{ url('users') }}/" + id + "/edit", function(data) {
            $('#user-id').val(data.id);
            $('#user-name').val(data.name);
            $('#user-email').val(data.email);
            $('#user-jabatan').val(data.jabatan_id);
            $('#user-shift').val(data.shift_id);
            
            // Reset the dropify input
            var drEvent = $('#user-photo').dropify();
            drEvent = drEvent.data('dropify');
            drEvent.resetPreview();
            drEvent.clearElement();

            if (data.detail_user && data.detail_user.avatar) {
                var avatarUrl = "{{ url('/') }}" + data.detail_user.avatar;
                drEvent.settings.defaultFile = avatarUrl;
                drEvent.destroy();
                drEvent.init();
            }

            $('#modal-user').modal('show');
        });
    });

    // Form submission
    $('#form-user').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var userId = $('#user-id').val();
        var url = userId ? "{{ url('users') }}/" + userId : "{{ route('users.store') }}";
        var method = userId ? 'PUT' : 'POST';

        // Password validation
        var password = $('#user-password').val();
        var passwordConfirm = $('#user-password-confirm').val();
        if (password || passwordConfirm) {
            if (password !== passwordConfirm) {
                Swal.fire('Error!', 'Password dan konfirmasi password tidak cocok.', 'error');
                return;
            }
        } else if (!userId) {
            Swal.fire('Error!', 'Password harus diisi untuk user baru.', 'error');
            return;
        }

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#modal-user').modal('hide');
                table.ajax.reload();
                Swal.fire('Berhasil!', 'User telah ' + (userId ? 'diperbarui.' : 'ditambahkan.'), 'success');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Terjadi kesalahan saat memproses data user.', 'error');
            }
        });
    });

    // Delete User
    var deleteId;
    $(document).on('click', '.delete-user', function() {
        deleteId = $(this).data('id');
        $('#modal-delete-user').modal('show');
    });

    $('#confirm-delete-user').click(function() {
        $.ajax({
            url: "{{ url('users') }}/" + deleteId,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#modal-delete-user').modal('hide');
                table.ajax.reload();
                Swal.fire('Berhasil!', 'User telah dihapus.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus user.', 'error');
            }
        });
    });

    // Fungsi untuk mereset form dan dropify
    function resetUserForm() {
        $('#user-form')[0].reset();
        var drEvent = $('#user-photo').dropify();
        drEvent = drEvent.data('dropify');
        drEvent.resetPreview();
        drEvent.clearElement();
    }

    // Event handler untuk tombol tambah user
    $(document).on('click', '.add-user', function() {
        $('#modal-title').text('Add User');
        $('#password-help').hide();
        resetModalForm();
        $('#modal-user').modal('show');
    });

    // Event handler untuk edit user (yang sudah ada sebelumnya)
    $(document).on('click', '.edit-user', function() {
        var id = $(this).data('id');
        $('#modal-title').text('Edit User');
        $('#password-help').show();
        
        $.get("{{ url('users') }}/" + id + "/edit", function(data) {
            resetUserForm(); // Reset form sebelum mengisi dengan data baru
            $('#user-id').val(data.id);
            $('#user-name').val(data.name);
            $('#user-email').val(data.email);
            $('#user-jabatan').val(data.jabatan_id);
            $('#user-shift').val(data.shift_id);
            
            var drEvent = $('#user-photo').dropify();
            drEvent = drEvent.data('dropify');

            if (data.detail_user && data.detail_user.avatar) {
                var avatarUrl = "{{ url('/') }}/" + data.detail_user.avatar;
                drEvent.settings.defaultFile = avatarUrl;
                drEvent.destroy();
                drEvent.init();
            }

            $('#modal-user').modal('show');
        });
    });

    // Event handler ketika modal ditutup
    $('#modal-user').on('hidden.bs.modal', function () {
        resetUserForm();
    });

});
</script>
@endsection