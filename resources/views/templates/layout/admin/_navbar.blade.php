<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light border-1">
    <ul class="navbar-nav">
        <li class="nav-item d-none">
            <a class="nav-link" data-widget="pushmenu" href="javascript:;" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <div class="d-flex flex-row justify-content-center align-items-center">
                <span class="nav-link text-bold">{{ $title ?? $menu['name'] }}</span>
                <div class="border-left text-center">&nbsp;</div>
                {!! app()->general->createBreadCrumbHtml($menuData, $menu['code']) !!}
            </div>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Account Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="javascript:;" title="Account">
                <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ session('email') }}</span>
                <div class="dropdown-divider"></div>
                <a href="{{ route('change-profile') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('change-password') }}" class="dropdown-item">
                    <i class="fas fa-key mr-2"></i> Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('sign-in-history') }}" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> Sign In History
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('/sign-out') }}" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="javascript:;" role="button" title="Change theme" id="changeTheme">
                <i class="fas fa-moon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="javascript:;" role="button" title="Clear cache" id="clearCache">
                <i class="fas fa-broom"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="javascript:;" role="button" title="Full Screen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
