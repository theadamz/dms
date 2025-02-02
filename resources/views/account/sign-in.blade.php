<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <base href="{{ url('/') }}" />
    <title>Sign In - {{ config('setting.general.web_name_short') }}</title>
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
    @vite([])
</head>

<body>
    <div class="hold-transition login-page">
        <div class="login-box">
            @if (Session::has('notification'))
                <div class="alert alert-{{ Session::get('notification')['type'] }} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon {{ Session::get('notification')['icon'] }}"></i>
                        {{ Session::get('notification')['title'] }}</h5>
                    {{ Session::get('notification')['message'] }}
                </div>
            @endif
            <div class="card card-outline">
                <div class="card-header text-center">
                    <a href="{{ url('/') }}" class="h5">{{ config('setting.general.web_name_short') }}</a>
                    <div class="d-flex flex-column text-muted text-secondary mt-1 text-xs">
                        <em>{{ config('setting.general.web_name') }}</em>
                        @if (!empty(config('setting.general.web_description')))
                            <em>{{ config('setting.general.web_description') }}</em>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('sign-in') }}" method="post" id="formSignIn" name="formSignIn">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                <input type="text" placeholder="Email" id="email" name="email" maxlength="255" value="{{ old('email') ?? '' }}" class="form-control @error('email') is-invalid @enderror" autofocus>
                                @error('email')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary input-group-text"
                                            id="showPassword" for="password">
                                        <i class="fas fa-eye"></i>
                                        <i class="fas fa-eye-slash d-none"></i>
                                    </button>
                                </div>
                                <input type="password" placeholder="Password" id="password" name="password" maxlength="255" autocomplete="off" value="{{ old('password') ?? '' }}" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                       value="1" checked>
                                <label class="form-check-label text-sm" for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <button type="submit" class="btn btn-outline-primary btn-block font-weight-bold" id="signIn" name="signIn">
                                <span class="indicator-label"><i class="fas fa-sign-in-alt mr-2"></i> Sign In</span>
                                <span class="indicator-progress d-none">
                                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                    @if (Route::has('password.request'))
                        <p class="mb-0">
                            <a href="{{ route('password.request') }}" class="text-sm">Forgot Password?</a>
                        </p>
                    @endif
                    @if (Route::has('sign-up'))
                        <p class="mb-0">
                            <a href="{{ route('sign-up') }}" class="text-sm">Sign Up</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="fixed-bottom d-none d-sm-block border-top bg-light">
            <div class="d-flex flex-row justify-content-between mt-1 px-2 py-2">
                <div class="float-left">
                    <div class="d-flex flex-row">
                        <a href="{{ url('/') }}" class="mr-3 font-weight-bold text-sm text-secondary">Home</a>
                        <a href="mailto:theadamz91@gmail.com" class="font-weight-bold text-sm text-secondary">Contact</a>
                    </div>
                </div>
                <div class="float-right">
                    <div class="font-weight-bold text-sm text-secondary">2024 &copy;
                        <a class="text-muted" href="mailto:theadamz91@gmail.com">
                            {!! config('setting.general.copyright') !!}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('assets/vendor/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('assets/vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/vendor/js/adminlte.min.js') }}"></script>
    <script src="{{ url('assets/vendor/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('assets/vendor/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    @if (config('setting.local.locale_short') !== 'en')
        <script
                src="{{ url('assets/vendor/plugins/jquery-validation/localization/messages_' . config('setting.local.locale_short') . '.min.js') }}"></script>
    @endif
    <script src="{{ url('assets/vendor/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ url('assets/js/lib/idle-timer/idle-timer.min.js') }}"></script>
    @foreach ($additionalJS as $file)
        @vite($file)
    @endforeach
</body>

</html>
