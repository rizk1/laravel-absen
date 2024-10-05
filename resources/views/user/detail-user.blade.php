@extends('layout.app-layout')
@section('title-page', 'Profile - ')
@section('title-content', 'Profile')

@section('css')
<link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
@endsection

@section('content')
<div class="row justify-content-md-center">
    <div class="col-12 {{ $isEditing ? 'col-md-6' : 'col-md-4' }}">
        <div class="card">
          <div class="card-header">
            <h4 class="mx-auto">{{ $isEditing ? 'Edit' : 'Keterangan' }} Karyawan</h4>
          </div>
          @error('error')
            <div class="alert alert-danger">
                <p class="m-0 text-center">{{ $message }}</p>
            </div>
          @enderror
          <form action="{{ route('user.saveOrUpdate', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body {{ $isEditing ? '' : 'text-center' }}">
              <div class="form-group mb-2">
                @if($isEditing)
                  <label>Foto Karyawan</label>
                  <input name="photo" type="file" class="dropify" data-height="100" data-allowed-file-extensions="png jpg jpeg" 
                         data-default-file="{{ $userDetail && $userDetail->avatar ? url($userDetail->avatar) : '' }}" />
                  @else
                    <img src="{{ $userDetail && $userDetail->avatar ? url($userDetail->avatar) : asset('assets/img/avatar/avatar-1.png')}}" 
                         alt="User Avatar" class="img-fluid" style="max-height: 100px;">
                  @endif
              </div>
              <div class="form-group mb-3">
                <label>Nama Karyawan</label>
                @if($isEditing)
                  <input type="text" name="name" class="form-control form-control-sm" value="{{ $user->name }}">
                @else
                  <p>{{ $user->name }}</p>
                @endif
              </div>
              <div class="form-group mb-3">
                <label>Email</label>
                @if($isEditing)
                  <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                @else
                  <p>{{ $user->email }}</p>
                @endif
              </div>
              <div class="form-group mb-3">
                  <label>Jabatan</label>
                  @if($isEditing)
                    <select name="jabatan" class="form-control form-control-sm">
                      @foreach ($jabatan as $item)
                          <option value="{{ $item->id }}" {{ ($user && $user->jabatan_id == $item->id) ? 'selected' : '' }}>{{ $item->jabatan }}</option>
                      @endforeach
                    </select>
                  @else
                    <p>{{ $user->jabatan->jabatan }}</p>
                  @endif
              </div>
              <div class="form-group mb-3">
                <label>Shift</label>
                @if($isEditing)
                  <select name="shift" class="form-control form-control-sm">
                    @foreach ($shift as $item)
                        <option value="{{ $item->id }}" {{ ($user && $user->shift_id == $item->id) ? 'selected' : '' }}>{{ $item->shift }}</option>
                    @endforeach
                  </select>
                @else
                  <p>{{ $user->shift->shift }}</p>
                @endif
              </div>
              @if(!$isEditing)
                <div class="form-group mb-3">
                  <label>Absen Terakhir</label>
                  <p>{{ $absen && $absen->created_at ? $absen->created_at->format('d M Y H:i:s') : 'Belum ada absen' }}</p>
                </div>
                <div class="form-group mb-3">
                  <label>Tipe Absen Terakhir</label>
                  <p>{{ $absen && $absen->created_at ? $absen->type : '-' }}</p>
                </div>
              @endif
            </div>
            @if($isEditing)
              <div class="m-4">
                  <button type="submit" class="btn btn-primary">Save</button>
              </div>
            @else
              <div class="mx-4 mb-4 d-flex justify-content-center">
                  <a href="{{ url('detail-user/?isEditing=true') }}" class="btn btn-primary mr-2">Update Profile</a>
                  <a href="{{route('absen')}}" class="btn btn-info">Absen</a>
              </div>
            @endif
          </form>
        </div>
      </div>
</div>
@endsection

@section('js')
@if($isEditing)
  <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
  <script>
      $('.dropify').dropify();
  </script>
@endif
@endsection