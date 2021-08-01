@extends('layout.app-layout')

@section('css')
{{-- <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"> --}}
@endsection

@section('content')
<div class="row justify-content-md-center">
    <div class="col-md-4">
        <div class="block">
            <div class="block-content">
                <input type="file" class="filepond">
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
{{-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> --}}
<script>
// FilePond.parse(document.body);
</script>
@endsection