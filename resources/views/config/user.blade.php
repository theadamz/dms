<x-layouts.admin>
    <!-- Toolbar -->
    <section class="content-header-fixed pt-0 px-0">
        <nav class="navbar navbar-expand navbar-white shadow-sm">
            <!-- Left -->
            <ul class="navbar-nav">
                @can('config-user-create')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-success" id="create" name="create"
                                data-toggle="modal" data-target="#modalFormInput">
                            <i class="fas fa-plus d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Create</span>
                        </button>
                    </li>
                @endcan
                @can('config-user-edit')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-warning" id="edit" name="edit">
                            <i class="fas fa-edit d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Edit</span>
                        </button>
                    </li>
                @endcan
                @can('config-user-delete')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-info" id="delete" name="delete">
                            <span class="indicator-label">
                                <i class="fas fa-times d-inline"></i>
                                <span class="ml-2 d-none d-sm-inline font-weight-bold">Inactive</span>
                            </span>
                            <span class="indicator-progress d-none">
                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            </span>
                        </button>
                    </li>
                @endcan
            </ul>

            <!-- Right -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mr-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh" name="refresh">
                        <i class="fas fa-sync d-inline"></i>
                        <span class="ml-2 d-none d-sm-inline font-weight-bold">Refresh</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="filter" name="filter"
                            data-toggle="modal" data-target="#modalFormFilter">
                        <i class="fas fa-filter d-inline"></i>
                        <span class="ml-2 d-none d-sm-inline font-weight-bold">Filter</span>
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
                        <table id="list_datatable" name="list_datatable" class="table display" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Timezone</th>
                                    <th class="text-center">Active</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.container-fluid -->
        </section>
    </section>
    <!-- /.content -->

    <!--begin::Modal - Form Input-->
    <div class="modal" tabindex="-1" id="modalFormInput" data-backdrop="static" data-keyboard="false"
         data-focus="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content position-absolute">
                <div class="modal-header align-items-center">
                    <h4 class="modal-title">Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="formInput" name="formInput" class="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Username <span class="text-danger">*</span></label>
                                    <input type="text" id="username" name="username" class="form-control font-weight-normal" placeholder="Username" value="" maxlength="255" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Email <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control font-weight-normal" placeholder="Email" value="" maxlength="255" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" class="form-control font-weight-normal" placeholder="Password" maxlength="150" autocomplete="off" />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary input-group-text" id="showPassword" for="password">
                                                <i class="fas fa-eye"></i>
                                                <i class="fas fa-eye-slash d-none"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Name <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control font-weight-normal" placeholder="Name" value="" maxlength="255" autocomplete="off" />
                                </div>
                            </div>
                            <div class="row col">
                                <div class="col-md-4">
                                    <x-inputs.picture label="Picture" element-name="picture" :no-picture-url="url('/assets/images/_photo_profile_blank.png')" />
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group fv-row">
                                        <label class="form-label font-weight-normal mb-1">Department <span class="text-danger">*</span></label>
                                        <select class="form-control font-weight-normal form-select2" id="department" name="department" data-dropdown-parent="#modalFormInput" data-allow-clear="false">
                                            <option value=""></option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group fv-row">
                                        <label class="form-label font-weight-normal mb-1">Role <span class="text-danger">*</span></label>
                                        <select class="form-control font-weight-normal form-select2" id="role" name="role" data-dropdown-parent="#modalFormInput" data-allow-clear="false">
                                            <option value=""></option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group fv-row">
                                        <label class="form-label font-weight-normal mb-1">Timezone <span class="text-danger">*</span></label>
                                        <select class="form-control font-weight-normal form-select2" id="timezone" name="timezone" data-dropdown-parent="#modalFormInput" data-allow-clear="false">
                                            <option value=""></option>
                                            @foreach ($timezones as $timezone)
                                                <option value="{{ $timezone['value'] }}"{{ $timezone['value'] === config('setting.local.timezone') ? ' selected' : '' }}>{{ $timezone['text'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group fv-row">
                                        <label class="form-label font-weight-normal mb-1">Active</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="true" id="is_active" name="is_active" checked />
                                            <label class="form-check-label" for="is_active">Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" form="formInput" class="btn btn-default" data-dismiss="modal" id="cancel" name="cancel">
                        <i class="fa fa-times mr-2"></i> Cancel
                    </button>
                    <button type="submit" form="formInput" class="btn btn-success" id="save" name="save">
                        <span class="indicator-label"><i class="fas fa-save mr-2"></i> Save</span>
                        <span class="indicator-progress d-none">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Form Input-->

    <!--begin::Modal - Form Filter-->
    <div class="modal" tabindex="-1" id="modalFormFilter" data-backdrop="static" data-keyboard="false"
         data-focus="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content position-absolute">
                <div class="modal-header align-items-center">
                    <h4 class="modal-title">Filter</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="formFilter" name="formFilter" class="form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Department</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_department" name="filter_department" data-dropdown-parent="#modalFormFilter" data-allow-clear="false">
                                        <option value=""></option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Role</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_role" name="filter_role" data-dropdown-parent="#modalFormFilter" data-allow-clear="false">
                                        <option value=""></option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Active</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_is_active" name="filter_is_active" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="reset" form="formFilter" class="btn btn-default" id="filterReset" name="filterReset">
                        <i class="fas fa-times-circle mr-2"></i> Reset
                    </button>
                    <button type="submit" form="formFilter" class="btn btn-primary" id="filterApply" name="filterApply">
                        <i class="fas fa-check mr-2"></i> Apply
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Form Filter-->

    <script>
        const timezone = "{{ session('timezone') }}";
    </script>
</x-layouts.admin>
