<x-layouts.admin>
    <!-- Toolbar -->
    <section class="content-header-fixed pt-0 px-0">
        <nav class="navbar navbar-expand navbar-white shadow-sm">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button type="submit" form="formInput" class="btn btn-sm btn-outline-success" id="save"
                        name="save">
                        <span class="indicator-label"><i class="fas fa-save d-inline"></i><span
                                class="ml-2 d-none d-sm-inline">Save</span></span>
                        <span class="indicator-progress d-none">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        </span>
                    </button>
                </li>
            </ul>
        </nav>
    </section>

    <!-- Main content -->
    <section class="content">
        <section class="flex-column-fluid">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form id="formInput" name="formInput" method="post">
                            @csrf
                            <div class="form-group row">
                                <label for="password" class="font-weight-normal mb-1 col-form-label col-sm-3">Old
                                    Password <span class="text-danger">*</span></label>
                                <div class="fv-row col-sm-9">
                                    <div class="input-group">
                                        <input type="password" placeholder="Old Password" id="password_old"
                                            name="password_old" maxlength="150" autocomplete="off" value=""
                                            class="form-control">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary input-group-text"
                                                id="showPasswordOld" for="password_old">
                                                <i class="fas fa-eye"></i>
                                                <i class="fas fa-eye-slash d-none"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="font-weight-normal mb-1 col-form-label col-sm-3">New
                                    Password <span class="text-danger">*</span></label>
                                <div class="fv-row col-sm-9">
                                    <div class="input-group">
                                        <input type="password" placeholder="New Password" id="password" name="password"
                                            maxlength="150" autocomplete="off" value="" class="form-control">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary input-group-text"
                                                id="showPasswordNew" for="password">
                                                <i class="fas fa-eye"></i>
                                                <i class="fas fa-eye-slash d-none"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password_confirmation"
                                    class="font-weight-normal mb-1 col-form-label col-sm-3">Confirm New Password
                                    <span class="text-danger">*</span></label>
                                <div class="fv-row col-sm-9">
                                    <div class="input-group">
                                        <input type="password" placeholder="Confirm New Password"
                                            id="password_confirmation" name="password_confirmation" maxlength="150"
                                            autocomplete="off" value="" class="form-control">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary input-group-text"
                                                id="showPasswordNewConfirm" for="password_confirmation">
                                                <i class="fas fa-eye"></i>
                                                <i class="fas fa-eye-slash d-none"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.container-fluid -->
        </section>
    </section>
    <!-- /.content -->
</x-layouts.admin>
