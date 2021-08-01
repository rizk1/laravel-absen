<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Absen</title>

        <meta name="description" content="">
        <meta name="author" content="Rizki">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="">
        <meta property="og:description" content="">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->

        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                <div class="bg-body-dark bg-pattern">
                    <div class="row mx-0 justify-content-center">
                        <div class="hero-static col-lg-6 col-xl-4">
                            <div class="content content-full overflow-hidden">
                                <!-- Header -->
                                <div class="py-30 text-center">
                                    <a class="link-effect font-w700" href="index.html">
                                        <span class="font-size-xl text-primary-dark">Absen</span><span class="font-size-xl">Byc</span>
                                    </a>
                                    <h1 class="h4 font-w700 mt-30 mb-10">Welcome to Your Dashboard</h1>
                                    <h2 class="h5 font-w400 text-muted mb-0">It’s a great day today!</h2>
                                </div>
                                <!-- END Header -->

                                <!-- Sign In Form -->
                                <!-- jQuery Validation functionality is initialized with .js-validation-signin class in js/pages/op_auth_signin.min.js which was auto compiled from _es6/pages/op_auth_signin.js -->
                                <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                @if(Request::get('action') == 'login' || Request::get('action') == '')   
                                <form action="{{url('/login')}}" method="post">
                                    @csrf                      
                                    <div class="block block-themed block-rounded block-shadow">
                                        <div class="block-header bg-gd-dusk">
                                            <h3 class="block-title">Please Sign In</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-wrench"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            {{-- @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <p class="m-0 text-center">Username atau password salah</p>
                                            </div>
                                            @endif    --}}
                                            @error('error')
                                            <div class="alert alert-danger">
                                                <p class="m-0 text-center">{{ $message }}</p>
                                            </div>
                                            @enderror
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" id="login-username" name="email" value="{{old('email')}}" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label for="login-password">Password</label>
                                                    <input type="password" class="form-control" id="login-password" name="password" required>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-sm-6 d-sm-flex align-items-center push">
                                                    <div class="custom-control custom-checkbox mr-auto ml-0 mb-0">
                                                        <input type="checkbox" class="custom-control-input" id="login-remember-me" name="remember_me">
                                                        <label class="custom-control-label" for="login-remember-me">Remember Me</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 text-sm-right push">
                                                    <button type="submit" class="btn btn-alt-primary">
                                                        <i class="si si-login mr-10"></i> Sign In
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block-content bg-body-light">
                                            <div class="form-group text-center">
                                                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="{{url('/auth?action=register')}}">
                                                    <i class="fa fa-plus mr-5"></i> Create Account
                                                </a>
                                                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="op_auth_reminder3.html">
                                                    <i class="fa fa-warning mr-5"></i> Forgot Password
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                @endif
                                @if(Request::get('action') == 'register') 
                                <form action="{{url('/register')}}" method="post">
                                    @csrf   
                                    <div class="block block-themed block-rounded block-shadow">
                                        <div class="block-header bg-gd-emerald">
                                            <h3 class="block-title">Please add your details</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-wrench"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label for="signup-username">Nama</label>
                                                    <input type="text" class="form-control" id="signup-username" name="name" value="{{old('name')}}" placeholder="eg: john_smith">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label for="signup-email">Email</label>
                                                    <input type="email" class="form-control" id="signup-email" name="email" value="{{old('email')}}" placeholder="eg: john@example.com">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label for="signup-password">Password</label>
                                                    <input type="password" class="form-control" id="signup-password" name="password" placeholder="********">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label for="signup-password-confirm">Password Confirmation</label>
                                                    <input type="password" class="form-control" id="signup-password-confirm" name="password_confirmation" placeholder="********">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-sm-12 text-center push">
                                                    <button type="submit" class="btn btn-alt-success">
                                                        <i class="fa fa-plus mr-10"></i> Create Account
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block-content bg-body-light">
                                            <div class="form-group text-center">
                                                <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="{{url('/auth?action=login')}}">
                                                    <i class="fa fa-user text-muted mr-5"></i> Sign In
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                @endif
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->

        <script src="assets/js/codebase.core.min.js"></script>
        <script src="assets/js/codebase.app.min.js"></script>
    </body>
</html>