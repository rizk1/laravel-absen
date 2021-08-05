@extends('layout.app-layout')

@section('content')
<h2 class="content-heading">Test Midtrans</h2>

<div class="block">
    <div class="block-content">
        <div class="row justify-content-center">
            <div class="col-md-2 col-4 text-center mb-20">
                <button id="absen" class="btn btn-absen btn-block pays">Bayar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript"src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-i0cQaNLjOAbsIIl8"></script>
<script>
$(".pays").on('click', function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // var dataId = $(this).attr('data-id');
    // console.log(dataId)
    $.ajax({
        method: 'POST',
        url: "{{url('payment')}}/",
        data: {
            _token: '{{ csrf_token() }}',
        },
        success: function(result) {
            console.log(result);
            window.snap.pay(result.token,{
                onSuccess: function(result){
                    console.log(result);
                },
                onPending: function(result){
                    console.log(result);
                },
                onError: function(result){
                    console.log(result);
                }
            });
        },
    });
});
$(document).ajaxComplete(function(){
    Swal.close();            
});
</script>
@endsection