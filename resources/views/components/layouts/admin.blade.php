<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <base href="{{ url('/') }}" />
    <title>{{ $title ?? $menu['name'] }} - {{ config('setting.general.web_name_short') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ config('setting.general.web_description') }}" />
    <meta name="keywords" content="{{ config('setting.general.web_keywords') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="_baseURL" content="{{ url('/') }}">
    <meta name="_csrf-token" content="{{ csrf_token() }}">
    <meta name="_id" content="{{ app()->general->getId() }}">
    <meta name="_locale_short" content="{{ config('setting.local.locale_short') }}" />
    <meta name="_locale" content="{{ config('setting.local.locale') }}" />
    <meta name="_action" content="{{ app()->general->getAction() }}" />
    <meta property="og:locale" content="{{ config('setting.local.locale') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ config('setting.general.web_name') }}" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:site_name" content="{{ config('setting.general.web_name') }}" />
    <link rel="canonical" href="{{ url('/') }}" />
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.png') }}" />
    @once
        @vite('resources/css/app.css')
        <!--font-->
        <link rel="stylesheet" href="{{ url('assets/vendor/font/inter.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/fontawesome-free/css/all.min.css') }}" type="text/css" />
        <!--alert-->
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/sweetalert2/sweetalert2.min.css') }}" />
        <!--datatable-->
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" />
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}" />
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/datatables-select/css/select.bootstrap4.min.css') }}" />
        <!--select2-->
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/select2/css/select2.min.css') }}" />
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" />
        <!-- daterange picker -->
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/daterangepicker/daterangepicker.css') }}" />
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
        <!-- Perfect scrollbar -->
        <link rel="stylesheet" href="{{ url('assets/vendor/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <!-- vendor -->
        <link rel="stylesheet" href="{{ url('assets/vendor/css/adminlte.min.css') }}" />
        @foreach (app()->general->getadditionalVendorCSS() as $fileLocation)
            <link rel="stylesheet" href="{{ $fileLocation }}" type="text/css" />
        @endforeach
        @foreach (app()->general->getadditionalCSS() as $fileLocation)
            @vite($fileLocation)
        @endforeach
        <link rel="stylesheet" href="{{ url('assets/css/custom.css') }}" type="text/css" />
        @stack('styles')
    @endonce
    @vite([])
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

    <!-- Site wrapper -->
    <div class="wrapper">
        @once
            @include('templates.layout.admin._preloader')
            @include('templates.layout.admin._navbar')
            @include('templates.layout.admin._sidebar')
        @endonce
        @include('templates.layout.admin._default')
        @once
            @include('templates.layout.admin._footer')
        @endonce
    </div>

    <!--begin::Javascript-->
    <script>
        const __permissions = {!! empty($permissions) ? 'null' : json_encode($permissions) !!};
        const __limit = {{ config('setting.page.default_limit') }};
        const __thousandSeparator = "{{ config('setting.local.numeric_thousand_separator') }}";
        const __decimalSeparator = "{{ config('setting.local.numeric_decimal_separator') }}";
        const __decimalPoint = "{{ config('setting.local.numeric_precision_length') }}";
        const __jsDateFormat = "{{ config('setting.local.js_date_format') }}";
        const __jsDateTimeFormat = "{{ config('setting.local.js_datetime_format') }}";
        const __jsTimeFormat = "{{ config('setting.local.js_time_format') }}";
        const __jsDateFormatMask = "{{ config('setting.local.js_date_format_mask') }}";
        const __jsDateTimeFormatMask = "{{ config('setting.local.js_date_format_mask') }}";
        const __timezone = "{{ session('timezone') }}";
    </script>
    @once
        <!--bootstrap-->
        <script src="{{ url('assets/vendor/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
        <!--moment-->
        <script src="{{ url('assets/vendor/plugins/moment/moment.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/moment/moment-with-locales.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/moment/moment-timezone-with-data.min.js') }}"></script>
        <!--mask-->
        <script src="{{ url('assets/vendor/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
        <!--alert-->
        <script src="{{ url('assets/vendor/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <!--datatable-->
        <script src="{{ url('assets/vendor/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/datatables-select/js/dataTables.select.min.js') }}"></script>
        <!--validation-->
        <script src="{{ url('assets/vendor/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script src="{{ url('assets/vendor/plugins/jquery-validation/additional-methods.min.js') }}"></script>
        @if (config('setting.local.locale_short') !== 'en')
            <script
                    src="{{ url('assets/vendor/plugins/jquery-validation/localization/messages_' . config('setting.local.locale_short') . '.min.js') }}"></script>
        @endif
        <!--select2-->
        <script src="{{ url('assets/vendor/plugins/select2/js/select2.full.min.js') }}"></script>
        <!-- date-range-picker -->
        <script src="{{ url('assets/vendor/plugins/daterangepicker/daterangepicker.js') }}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{ url('assets/vendor/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
        <!-- Perfect scrollbar -->
        <script src="{{ url('assets/vendor/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
        <!-- other -->
        <script src="{{ url('assets/vendor/js/adminlte.min.js') }}"></script>
        <script src="{{ url('assets/js/lib/maxlength/bootstrap-maxlength.min.js') }}"></script>
        <!--begin::Custom Javascript(used for this page only)-->
        <script src="{{ url('assets/js/lib/idle-timer/idle-timer.min.js') }}"></script>
        @vite('resources/js/app.js')
        <!--begin::Custom Javascript(used for this page only)-->
        <script src="{{ url('assets/js/lib/idle-timer/idle-timer.min.js') }}"></script>
        @foreach (app()->general->getAdditionalVendorJS() as $fileLocation)
            <script src="{{ $fileLocation }}"></script>
        @endforeach
        @foreach (app()->general->getAdditionalJS() as $fileLocation)
            @vite($fileLocation)
        @endforeach
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
        @stack('scripts')
    @endonce
</body>

</html>
