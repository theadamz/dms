<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <base href="{{ url('/') }}" />
    <title>Reset Password - {{ config('setting.general.web_name_short') }}</title>
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
                <div class="card card-outline">
                    <div class="card-header text-center">
                        <h5>Reset Password</h5>
                        <div class="d-flex flex-column text-muted text-secondary mt-1 text-xs">
                            Please enter your new password
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('password.update') }}" method="post" id="formResetPassword"
                            name="formResetPassword">
                            @csrf
                            <input type="hidden" value="{{ $token }}" id="token" name="token" />
                            <input type="hidden" value="{{ $email }}" id="email" name="email" />
                            <div class="form-group fv-row">
                                <label class="form-label font-weight-normal mb-1">Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" placeholder="Password" id="password" name="password"
                                    maxlength="255" autocomplete="off" value="{{ old('password') ?? '' }}"
                                    class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group fv-row mb-5">
                                <label class="form-label font-weight-normal mb-1">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" placeholder="Password" id="password_confirmation"
                                    name="password_confirmation" maxlength="255" autocomplete="off"
                                    value="{{ old('password_confirmation') ?? '' }}"
                                    class="form-control @error('password_confirmation ') is-invalid @enderror">
                                @error('password_confirmation ')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-success btn-block font-weight-bold" id="save"
                                    name="save">
                                    <span class="indicator-label"> Save</span>
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
