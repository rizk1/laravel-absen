@extends('layout.app-layout')

@section('css')
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
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
        <div class="text-center"><button id="absen" class="btn btn-absen">Absen</button></div>
    </div>
</div>
<div class="block">
    <div class="block-content">
        
         <br />
         {{-- <small>
           <a 
            href="https://maps.google.com/maps?q='+data.lat+','+data.lon+'&hl=es;z=14&amp;output=embed" 
            style="color:#0000FF;text-align:left" 
            target="_blank"
           >
             See map bigger
           </a>
         </small> --}}
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
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
        navigator.geolocation.getCurrentPosition(showPosition);
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
    }
});
</script>
@endsection