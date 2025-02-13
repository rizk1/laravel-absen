@extends('layout.app-layout')
@section('title-page', 'Data Absen - ')
@section('title-content', 'Data Absen')

@section('css')
{{-- <link rel="stylesheet" id="css-main" href="{{asset('assets/js/datatables/dataTables.bootstrap4.css')}}"> --}}
<link rel="stylesheet" href="{{asset('assets/modules/datatables/datatables.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.map-responsive{
    overflow:hidden;
    padding-bottom:56.25%;
    position:relative;
    height:0;
}

.map-responsive iframe{
    left:0;
    top:0;
    height:100%;
    width:100%;
    position:absolute;
}

.card-header-action {
    display: flex;
    align-items: center;
    gap: 10px;
}

.select2-container {
    min-width: 200px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 42px;
}
</style>
@endsection

@section('content')
<h2 class="section-title">Data Absen</h2>
<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header">
        <h4>Data Absen</h4>
        <div class="card-header-action">
            @if(Auth::user()->jabatan_id == 1)
            <select id="user-filter" class="form-control">
                <option value="all">Semua User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @endif
            <a href="{{ route('download-absen-pdf') }}" class="btn btn-primary" id="download-pdf">Download PDF</a>
        </div>
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="tabelAbsen">
                <thead>                                 
                    <tr>
                        <th class="text-center"></th>
                        <th>Nama</th>
                        <th class="d-none d-sm-table-cell">Email</th>
                        <th class="d-none d-sm-table-cell">Tipe</th>
                        <th class="d-none d-sm-table-cell">Shift</th>
                        <th class="d-none d-sm-table-cell">Waktu Absen</th>
                        <th class="d-none d-sm-table-cell">Tanggal</th>
                        <th class="d-none d-sm-table-cell">Peta Absen</th>
                        <th class="text-center" style="width: 15%;">Aksi</th>
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
@endsection

@section('js')
<script src="{{asset('assets/modules/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/modules/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/js/page/modules-datatables.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://momentjs.com/downloads/moment.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    @if(Auth::user()->jabatan_id == 1)
    // Initialize Select2 with delay
    setTimeout(function() {
        $('#user-filter').select2({
            placeholder: "Pilih User",
            allowClear: true
        });
        console.log("Select2 initialized successfully");
    }, 500);
    @endif

    var table = $('#tabelAbsen').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('data-absen') }}",
            data: function (d) {
                @if(Auth::user()->jabatan_id == 1)
                d.user_id = $('#user-filter').val();
                @endif
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'user.name', name: 'user.name'},
            {data: 'user.email', name: 'user.email'},
            {data: 'type', name: 'type'},
            {data: 'shift.shift', name: 'shift.shift'},
            {data: 'jam_absen', name: 'jam_absen'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'map_button', name: 'map_button', orderable: false, searchable: false},
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row, meta) {
                    var actions = $(data).filter('button, a').not('.delete-absen');
                    if ({{ Auth::user()->jabatan_id }} == 1) {
                        actions = actions.add('<button class="btn btn-danger btn-sm delete-absen" data-id="' + row.id + '"><i class="fa fa-trash"></i></button>');
                    }
                    return $('<div>').append(actions).html();
                }
            }
        ],
        columnDefs: [
            {
                targets: '_all',
                className: 'd-sm-table-cell'
            },
            {
                targets: [0, 8],
                className: 'text-center'
            }
        ]
    });

    $(document).on('click', '.map-show-data', function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        var dataId = $(this).attr('data-id');
        console.log(dataId)
        $.ajax({
            method: 'GET',
            url: "{{url('map-data')}}/" + dataId,
            success: function(data) {
                if (data.msg == 'success') {
                    // console.log(data);
                    $('#iframe-map').remove();
                    $('#map-data').append(data.mapData);
                    $('#modal-map').modal('show');
                } else {
                    // console.log(data);
                    Swal.fire({
                        title: data.alert,
                        type: data.type
                    });
                }
            },
        });
    });

    @if(Auth::user()->jabatan_id == 1)
    // Handle Select2 change event
    $('#user-filter').on('change', function(){
        table.ajax.reload();
    });

    $('#download-pdf').click(function(e){
        e.preventDefault();
        var url = "{{ route('download-absen-pdf') }}";
        if(userId && userId !== 'all') {
            url += '?user_id=' + userId;
        }
        window.location.href = url;
    });
    
    $(document).on('click', '.delete-absen', function(e) {
        e.preventDefault();
        var absenId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'DELETE',
                    url: "{{url('delete-absen')}}/" + absenId,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                'The attendance record has been deleted.',
                                'success'
                            ).then(() => {
                                table.ajax.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to delete the attendance record.',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the attendance record.',
                            'error'
                        );
                    }
                });
            }
        });
    });
    @endif
});
</script>
@endsection