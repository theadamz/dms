<!-- Main Sidebar Container -->
<aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-0 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link d-flex justify-content-center align-items-center border-right">
        <span class="brand-text font-weight-bold">{{ config('setting.general.web_name_short') }}</span>
    </a>

    <!-- Sidebar user (optional) -->
    <div class="user-panel user-panel-fixed d-flex align-items-center">
        <div class="image">
            <img src="{{ session('picture') }}" class="img-circle elevation-2" alt="{{ session('name') }}">
        </div>
        <div class="info pt-1">
            <a href="{{ url('profile') }}" class="d-block text-light">{{ session('name') }}</a>
        </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="nav-panel-search border border-top-0 border-left-0 border-right-0 border-secondary">
        <div class="form-inline justify-content-center px-2 pt-2">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="text" placeholder="Search"
                       aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                data-accordion="true">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                {!! $menuHTML !!}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->

    <div class="sidebar-custom">
        <a href="{{ route('sign-out') }}" class="btn btn-default hide-on-collapse btn-block">
            <i class="fas fa-sign-out-alt"></i>
            <span class="ml-2 hide-on-collapse-all">Sign Out</span>
        </a>
    </div>
    <!-- /.sidebar-custom -->
</aside>
<!-- ./ Main Sidebar Container -->
