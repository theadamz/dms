<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <base href="{{ url('/') }}" />
    <title>Forgot Password - {{ config('setting.general.web_name_short') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ config('setting.general.web_description') }}" />
    <meta name="keywords" content="{{ config('setting.general.web_keywords') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="_baseURL" content="{{ url('/') }}">
    <meta name="_csrf-token" content="{{ csrf_token() }}">
    <meta name="_id" content="">
    <meta name="_locale_short" content="{{ config('setting.local.locale_short') }}" />
    <meta name="_locale" content="{{ config('setting.local.locale') }}" />
    <meta property="og:locale" content="{{ config('setting.local.locale') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ config('setting.general.web_name') }}" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:site_name" content="{{ config('setting.general.web_name') }}" />
    <link rel="canonical" href="{{ url('/') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/font/inter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/adminlte.min.css') }}">
    <!--alert-->
    <link rel="stylesheet" href="{{ url('assets/vendor/plugins/sweetalert2/sweetalert2.min.css') }}">
    <!--select2-->
    <link rel="stylesheet" href="{{ url('assets/vendor/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ url('assets/vendor/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @vite([])
</head>

<body>
    <div class="vh-100 bg-light">
        <div class="d-flex flex-column h-100 justify-content-center align-items-center">
            <div class="w-25">
                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Warning!</h5>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('message'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Reset Link sent!</h5>
                        {{ Session::get('message') }}
                    </div>
                @endif
                <div class="card card-outline">
                    <div class="card-header text-center">
                        <h5>Forgot Password</h5>
                        <div class="d-flex flex-column text-muted text-secondary mt-1 text-xs">
                            Please enter your email
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('password.email') }}" method="post" id="formForgotPassword"
                            name="formForgotPassword">
                            @csrf
                            <div class="form-group fv-row">
                                <label class="form-label font-weight-normal mb-1">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" id="email" name="email"
                                    class="form-control font-weight-normal @error('email') is-invalid @enderror"
                                    placeholder="Email" value="{{ old('email') ?? '' }}" maxlength="255"
                                    autocomplete="off" autofocus />
                                <small class="text-muted">Please check your mail after sucess send link
                                    verification</small>
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-outline-primary btn-block font-weight-bold"
                                    id="send" name="send">
                                    <span class="indicator-label"><i class="fas fa-paper-plane mr-2"></i> Send</span>
                                    <span class="indicator-progress d-none">
                                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                        <p class="mb-0">
                            <a href="{{ route('sign-in') }}" class="text-sm">Sign In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @once
        <!--bootstrap-->
        <script src="{{ url('assets/vendor/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ url('assets/vendor/js/adminlte.min.js') }}"></script>
        <!--validation-->
        <script src="{{ url('assets/vendor/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/jquery-validation/additional-methods.min.js') }}"></script>
        @if (config('setting.local.locale_short') !== 'en')
            <script
                src="{{ url('assets/vendor/plugins/jquery-validation/localization/messages_' . config('setting.local.locale_short') . '.min.js') }}">
            </script>
        @endif
        <!--alert-->
        <script src="{{ url('assets/vendor/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        @vite('resources/js/app.js')
        @foreach ($additionalJS as $file)
            @vite($file)
        @endforeach
    @endonce
</body>

</html>
