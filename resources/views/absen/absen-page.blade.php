@extends('layout.app-layout')
@section('title-page', 'Absen - ')
@section('title-content', 'Absen')
@section('css')
    {{-- <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'> --}}
    <style>
        .clock {
            height: 100px;
            width: 70%;
            line-height: 100px;  
            margin: 55px auto !important;
            padding: 0 50px;
            background: #222;
            color: #eee;
            font-size: 40px !important;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 0 7px #222;
            text-shadow: 0 0 3px #fff;
        }
        .btn-absen{
            color: #eee;
            background: #222;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="block">
                        <div class="block-content">
                            <div id="clock" class="clock">loading ...</div>
                            <div class="row justify-content-center">
                                <div class="col-md-2 col-4 text-center mb-20">
                                    <button id="absen" class="btn btn-dark btn-block" data-toggle="modal" data-target="#absenModal">Absen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
<div class="modal fade" id="absenModal" tabindex="-1" role="dialog" aria-labelledby="absenModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="absenModalLabel">Pilih Shift dan Tipe Absen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="absenForm">
                    <div class="form-group">
                        <label for="shift">Pilih Shift</label>
                        <select class="form-control" id="shift" name="shift">
                            @foreach ($shift as $item)
                                <option value="{{$item->id}}">{{$item->shift}}</option>
                                
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipeAbsen">Tipe Absen</label>
                        <select class="form-control" id="tipeAbsen" name="tipeAbsen">
                            <option value="masuk">Masuk</option>
                            <option value="lembur">Lembur</option>
                            <option value="pulang">Pulang</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitAbsen">Absen</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FitText.js/1.1/jquery.fittext.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script type="text/javascript"src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-i0cQaNLjOAbsIIl8"></script>
    <script>
        $( document ).ready(function() {
            $('#clock').fitText(1.3);
            function update() {
                // $('#clock').html(moment().format('D. MMMM YYYY H:mm:ss'));
                $('#clock').html(moment().format('H:mm:ss'));
            }
            setInterval(update, 1000);
        });
    </script>
    <script>
        $("#submitAbsen").on('click', function(e) {
            e.preventDefault();
            var formData = $("#absenForm").serialize();
            var longitude = 0;
            var latitude = 0;

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            formData += '&_token=' + csrfToken;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    longitude = position.coords.longitude;
                    latitude = position.coords.latitude;

                    formData += '&long=' + longitude + '&lat=' + latitude;

                    $.ajax({
                        method: 'POST',
                        url: "{{url('absen')}}",
                        data: formData,
                        success: function(data) {
                            console.log(data);
                            if (data.msg == 'success') {
                                Swal.fire({
                                    title: data.alert,
                                    text: data.text,
                                    icon: data.type
                                });
                                $('#absenModal').modal('hide');
                            } else {
                                Swal.fire({
                                    title: data.alert,
                                    text: data.text,
                                    icon: data.type
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Something went wrong!',
                                icon: 'error'
                            });
                        }
                    });
                }, function(error) {
                    showError(error);
                });
            } else {
                Swal.fire({
                    title: 'Geolocation not supported',
                    icon: 'warning'
                });
            }
        });

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    Swal.fire({
                        title: 'User denied the request for Geolocation.',
                        icon: 'warning'
                    });
                    break;
                case error.POSITION_UNAVAILABLE:
                    Swal.fire({
                        title: 'Location information is unavailable.',
                        icon: 'warning'
                    });
                    break;
                case error.TIMEOUT:
                    Swal.fire({
                        title: 'The request to get user location timed out.',
                        icon: 'warning'
                    });
                    break;
                case error.UNKNOWN_ERROR:
                    Swal.fire({
                        title: 'An unknown error occurred.',
                        icon: 'warning'
                    });
                    break;
            }
        }
    </script>
@endsection