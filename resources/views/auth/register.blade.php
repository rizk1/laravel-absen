@extends('layout.auth-layout')
@section('title', 'Register')
@section('content')
<div class="container mt-5">
    <div class="row">
      <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

        <div class="card card-primary mt-5">
          <div class="card-header"><h4>Register</h4></div>

          <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{url('/register')}}" class="needs-validation" novalidate="">
                @csrf
                <div class="form-group">
                    <label for="email">Nama</label>
                    <input id="email" type="text" class="form-control" value="{{old('name')}}" name="name" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in your email
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" value="{{old('email')}}" name="email" required autofocus>
                    <div class="invalid-feedback">
                    Please fill in your email
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" required>
                    <div id="pwindicator" class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                    </div>
                    <div class="invalid-feedback">
                    please fill in your password
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Password Confirmation</label>
                    </div>
                    <input id="password" type="password" class="form-control" name="password_confirmation" required>
                    <div class="invalid-feedback">
                    please fill in your password
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Register
                    </button>
                </div>
            </form>

          </div>
        </div>
        <div class="mt-5 text-muted text-center">
          Already have an account? <a href="{{url('/auth?action=login')}}">Login</a>
        </div>
      </div>
    </div>
</div>
@endsection

@section('js')
<script src="assets/modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
<script src="assets/modules/jquery-selectric/jquery.selectric.min.js"></script>
@endsection