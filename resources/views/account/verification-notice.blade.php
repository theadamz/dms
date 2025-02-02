<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <base href="{{ url('/') }}" />
    <title>Verification Notice - {{ config('setting.general.web_name_short') }}</title>
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
            <div class="w-50">
                @if (Session::has('notification'))
                    <div class="alert alert-{{ Session::get('notification')['type'] }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon {{ Session::get('notification')['icon'] }}"></i>
                            {{ Session::get('notification')['title'] }}</h5>
                        {{ Session::get('notification')['message'] }}
                    </div>
                @endif
                <div class="card card-outline card-warning">
                    <div class="card-header text-center">
                        <h5>Verification Notice</h5>
                        <div class="d-flex flex-column text-muted text-secondary mt-1 text-xs">
                            Please verify your email before you continue.
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('verification.send') }}" method="post" id="formResend"
                            name="formResend">
                            @csrf
                            <div class="col py-5">
                                <div class="callout callout-warning">
                                    <h5>You email has not been verified</h5>
                                    <p>Please verify you email first before continue, if you still haven't receive
                                        verifiy email yet. Plase use resend button bellow to try again.</p>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold" id="resend"
                                    name="resend">
                                    <span class="indicator-label"><i class="fas fa-paper-plane mr-2"></i> Resend</span>
                                    <span class="indicator-progress d-none">
                                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                        <p class="mb-0">
                            <a href="{{ session('def_path') }}" class="text-sm">Home</a>
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
        <!--alert-->
        <script src="{{ url('assets/vendor/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        @vite('resources/js/app.js')
        @foreach ($additionalJS as $file)
            @vite($file)
        @endforeach
    @endonce
</body>

</html>
