<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <base href="{{ url('/') }}" />
    <title>@yield('title')</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ config('setting.general.web_description') }}" />
    <meta name="keywords" content="{{ config('setting.general.web_keywords') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="_baseURL" content="{{ url('/') }}">
    <meta property="og:locale" content="{{ config('setting.local.locale') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ config('setting.general.web_name') }}" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:site_name" content="{{ config('setting.general.web_name') }}" />
    <link rel="canonical" href="{{ url('/') }}" />
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.png') }}" />
    @once
        <!--font-->
        <link href="{{ url('assets/vendor/font/inter.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('assets/vendor/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet"
              type="text/css" />
        <!-- Perfect scrollbar -->
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/perfect-scrollbar/perfect-scrollbar.css') }}">
        <!-- vendor -->
        <link rel="stylesheet" href="{{ url('assets/vendor/css/adminlte.min.css') }}">
        <link href="{{ url('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    @endonce
    @vite([])
</head>

<body>

    <div class="d-flex vh-100 align-items-center justify-content-center">
        <!-- Main content -->
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-warning"> @yield('code')</h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> @yield('title')</h3>
                    <p class="bg-light rounded p-3">@yield('message')</p>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mr-3"><i class="fas fa-arrow-left mr-2"></i> Back</a>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary mr-3"><i class="fas fa-home mr-2"></i> Home</a>
                    @auth
                        <a href="{{ route('sign-out') }}" class="btn btn-outline-secondary mr-3"><i class="fas fa-sign-out-alt mr-2"></i> Sign Out</a>
                    @endauth
                </div>
            </div>
            <!-- /.error-page -->
        </section>
        <!-- /.content -->
    </div>

    <!--begin::Javascript-->
    @once
        <!--bootstrap-->
        <script src="{{ url('assets/vendor/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Perfect scrollbar -->
        <script src="{{ url('assets/vendor/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
        <!-- other -->
        <script src="{{ url('assets/vendor/js/adminlte.min.js') }}"></script>
        <!--end::Javascript-->
    @endonce
</body>

</html>
