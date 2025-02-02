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
                                <label for="password" class="font-weight-normal mb-1 col-form-label col-sm-3">Username <span class="text-danger">*</span></label>
                                <div class="fv-row col-sm-9">
                                    <input type="text" placeholder="Username" id="username" name="username" maxlength="255" autocomplete="off" value="" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="font-weight-normal mb-1 col-form-label col-sm-3">Email <span class="text-danger">*</span></label>
                                <div class="fv-row col-sm-9">
                                    <input type="text" placeholder="Email" id="email" name="email" maxlength="255" autocomplete="off" value="" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password_confirmation" class="font-weight-normal mb-1 col-form-label col-sm-3">Name <span class="text-danger">*</span></label>
                                <div class="fv-row col-sm-9">
                                    <input type="text" placeholder="Name" id="name" name="name" maxlength="255" autocomplete="off" value="" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="timezone" class="font-weight-normal mb-1 col-form-label col-sm-3">Timezone</label>
                                <div class="fv-row col-sm-9">
                                    <select class="form-control font-weight-normal form-select2" id="timezone" name="timezone" data-allow-clear="false">
                                        <option value=""></option>
                                        @foreach ($timezones as $timezone)
                                            <option value="{{ $timezone['value'] }}">{{ $timezone['text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="last_sign_in" class="font-weight-normal mb-1 col-form-label col-sm-3">Last Sign In</label>
                                <div class="fv-row col-sm-9">
                                    <input type="text" placeholder="Last Sign In" id="last_sign_in" name="last_sign_in" autocomplete="off" value="" class="form-control-plaintext" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="last_update" class="font-weight-normal mb-1 col-form-label col-sm-3">Last Update</label>
                                <div class="fv-row col-sm-9">
                                    <input type="text" placeholder="Last Update" id="last_update" name="last_update" autocomplete="off" value="" class="form-control-plaintext" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="last_change_password" class="font-weight-normal mb-1 col-form-label col-sm-3">Last Change Password</label>
                                <div class="fv-row col-sm-9">
                                    <input type="text" placeholder="Last Change Password" id="last_change_password" name="last_change_password" autocomplete="off" value="" class="form-control-plaintext" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password_confirmation" class="font-weight-normal mb-1 col-form-label col-sm-3">Picture</label>
                                <div class="fv-row col-sm-2">
                                    <x-inputs.picture label="Picture" element-name="picture" :no-label="true" :no-picture-url="url('/assets/images/_photo_profile_blank.png')" />
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
