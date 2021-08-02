@extends('layout.app-layout')

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
        text-align: center;
        border-radius: 15px;
        box-shadow: 0 0 7px #222;
        text-shadow: 0 0 3px #fff;
    }
    .btn-absen{
        /* box-shadow: 0 0 7px #222; */
        /* text-shadow: 0 0 3px #fff; */
        color: #eee;
        background: #222;
    }
</style>
@endsection

@section('content')
<h2 class="content-heading">Absensi</h2>

<div class="block">
    <div class="block-content">
        <div id="clock" class="clock">loading ...</div>
        <div class="row justify-content-md-center">
            <div class="col-md-2 text-center mb-20">
                <button id="absen" class="btn btn-absen btn-block">Absen</button>
            </div>
            <div class="col-md-2 text-center mb-20">
                <button id="izin" class="btn btn-absen btn-block">Izin</button>
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
$("#absen").on('click', function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var longitude = 0;
    var latitude = 0;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } 

    function showPosition(position) {
        var longitude = position.coords.longitude;
        var latitude = position.coords.latitude;
        
        $.ajax({
            method: 'POST',
            url: "{{url('absen')}}",
            data: {
                _token: '{{ csrf_token() }}',
                long: longitude,
                lat: latitude
            },
            success: function(data) {
                console.log(data);
                if (data.msg == 'success') {
                    Swal.fire({
                        title: data.alert,
                        text: data.text,
                        icon: data.type
                    });
                } 
                else if (data.msg == 'warning') {
                    Swal.fire({
                        title: 'Waktu absen habis',
                        text: "Apakah anda ingin tetap absen terlambat?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                method: 'POST',
                                url: "{{url('absen-telat')}}",
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    isTelat: 1,
                                    long: longitude,
                                    lat: latitude
                                },
                                success: function(data) {
                                    // console.log(data)
                                    Swal.fire({
                                        title: data.alert,
                                        icon: data.type
                                    })
                                },
                            });
                            
                        }
                    })
                }
                else {
                    // console.log(data);
                    Swal.fire({
                        title: data.alert,
                        text: data.text,
                        icon: data.type
                    });
                }
            },
        });
    }

    function showError(error) {
        console.log(error)
        switch(error.code) {
            case error.PERMISSION_DENIED:
            Swal.fire({
                title: 'User denied the request for Geolocation.',
                icon: 'warning'
            })
            break;
            case error.POSITION_UNAVAILABLE:
            Swal.fire({
                title: 'Location information is unavailable.',
                icon: 'warning'
            })
            break;
            case error.TIMEOUT:
            Swal.fire({
                title: 'The request to get user location timed out.',
                icon: 'warning'
            })
            break;
            case error.UNKNOWN_ERROR:
            Swal.fire({
                title: 'An unknown error occurred.',
                icon: 'warning'
            })
            break;
        }
    }
});
</script>
@endsection