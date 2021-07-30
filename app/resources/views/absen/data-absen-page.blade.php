@extends('layout.app-layout')

@section('css')
<link rel="stylesheet" id="css-main" href="{{asset('assets/js/datatables/dataTables.bootstrap4.css')}}">
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
</style>
@endsection

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Data Absensi</h3>
    </div>
    <div class="block-content block-content-full">
        <!-- DataTables functionality is initialized with .js-dataTable-full-pagination class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
            <thead>
                <tr>
                    <th class="text-center"></th>
                    <th>Name</th>
                    <th class="d-none d-sm-table-cell">Email</th>
                    <th class="d-none d-sm-table-cell">Status</th>
                    <th class="d-none d-sm-table-cell">Tipe</th>
                    <th class="d-none d-sm-table-cell">Waktu Absen</th>
                    <th class="d-none d-sm-table-cell">Tanggal</th>
                    <th class="d-none d-sm-table-cell">Peta Absen</th>
                    <th class="text-center" style="width: 15%;">Profile</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $absen)
                <tr>
                    <td class="text-center">1</td>
                    <td class="font-w600">{{$absen->user->name}}</td>
                    <td class="d-none d-sm-table-cell">{{$absen->user->email}}</td>
                    @if ($absen->status == 'tepat waktu')
                    <td class="d-none d-sm-table-cell">
                        <span class="badge badge-success">Tepat Waktu</span>
                    </td> 
                    @endif
                    @if ($absen->status == 'telat')
                    <td class="d-none d-sm-table-cell">
                        <span class="badge badge-danger">Terlambat</span>
                    </td> 
                    @endif
                    <td class="d-none d-sm-table-cell">{{$absen->type}}</td>
                    <td class="d-none d-sm-table-cell">{{$absen->jam_absen}}</td>
                    <td class="d-none d-sm-table-cell">{{$absen->tanggal}}</td>
                    <td class="d-none d-sm-table-cell">
                        <button type="button" class="btn btn-alt-info" id="map-show-data" data-id="{{$absen->id}}">Lihat Detail</button>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View Customer">
                            <i class="fa fa-user"></i>
                        </button>
                    </td>
                </tr> 
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Large Modal -->
<div class="modal fade" id="modal-large" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Peta Absen</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div id="map-data" class="map-responsive">
                        <iframe 
                        width="600" 
                        height="350" 
                        frameborder="0" 
                        allowfullscreen=""
                        src="https://maps.google.com/maps?q=+-6.3517663+,+106.9799218+&hl=id&z=14&amp;output=embed"
                        ></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Large Modal -->
@endsection

@section('js')
<script src="{{asset('assets/js/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/be_tables_datatables.min.js')}}"></script>
<script>
$("#map-show-data").on('click', function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        method: 'POST',
        url: "{{url('absen')}}",
        data: {
            _token: '{{ csrf_token() }}',
        },
        success: function(data) {
            if (data.msg == 'success') {
                // console.log(data);
                swal({
                    title: data.alert,
                    type: data.type
                });
            } else {
                // console.log(data);
                swal({
                    title: data.alert,
                    type: data.type
                });
            }
        },
    });
});
</script>
@endsection