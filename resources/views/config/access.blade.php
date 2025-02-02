<x-layouts.admin>
    <!-- Toolbar -->
    <section class="content-header-fixed pt-0 px-0">
        <nav class="navbar navbar-expand navbar-white shadow-sm">
            <!-- Left -->
            <ul class="navbar-nav">
                @can('config-role-access-create')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-success" id="create" name="create"
                            data-toggle="modal" data-target="#modalFormInput">
                            <i class="fas fa-plus d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Add</span>
                        </button>
                    </li>
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-dark" id="duplicate" name="duplicate"
                            data-toggle="modal" data-target="#modalFormDuplicate">
                            <i class="fas fa-copy d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Duplicate</span>
                        </button>
                    </li>
                @endcan
                @can('config-role-access-edit')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-warning" id="edit" name="edit">
                            <i class="fas fa-edit d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Edit</span>
                        </button>
                    </li>
                @endcan
                @can('config-role-access-delete')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-danger" id="delete" name="delete">
                            <span class="indicator-label">
                                <i class="fas fa-trash d-inline"></i>
                                <span class="ml-2 d-none d-sm-inline font-weight-bold">Delete</span>
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
                <li class="nav-item">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh" name="refresh">
                        <i class="fas fa-sync d-inline"></i>
                        <span class="ml-2 d-none d-sm-inline font-weight-bold">Refresh</span>
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
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Role</label>
                                    <select class="form-control font-weight-normal" id="role" name="role"
                                        data-placeholder="Select Role">
                                        <option value=""></option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Search</label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-search"></span>
                                            </div>
                                        </div>
                                        <input type="search" class="form-control" aria-controls="list_datatable"
                                            placeholder="Search">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--begin: Datatable-->
                        <table id="list_datatable" name="list_datatable" class="table display" style="width: 100%;">
                            <thead>
                                <tr
                                    class="text-gray-900 fw-bolder align-middle border-top border-top-dashed border-bottom border-bottom-dashed border-secondary">
                                    <th>#</th>
                                    <th>Access</th>
                                    <th>Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!--end: Datatable-->
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
                    <h4 class="modal-title">Add Access</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="formInput" name="formInput" class="form">
                        <!--begin::Row-->
                        <div class="row mb-5">
                            <div class="form-group fv-row col-12">
                                <label class="form-label font-weight-normal mb-1">Select 1 or more</label>
                                <select class="form-control font-weight-normal" id="access_lists" name="access_lists"
                                    data-placeholder="Select" multiple="multiple"
                                    data-dropdown-parent="#modalFormInput">
                                    <option value=""></option>
                                    @foreach ($accesses as $access)
                                        <option value="{{ $access['code'] }}">{{ $access['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--end::Row-->
                    </form>
                    <!--end::Form-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" form="formInput" class="btn btn-default" data-dismiss="modal"
                        id="cancel" name="cancel"><i class="fa fa-times mr-2"></i> Cancel
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

    <!--begin::Modal - Form Duplicate-->
    <div class="modal" tabindex="-1" id="modalFormDuplicate" data-backdrop="static" data-keyboard="false"
        data-focus="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content position-absolute">
                <div class="modal-header align-items-center">
                    <h4 class="modal-title">Duplicate Access</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="formDuplicate" name="formDuplicate" class="form">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="form-group fv-row col-12">
                                <label class="form-label font-weight-normal mb-1">From Role</label>
                                <select class="form-control font-weight-normal" id="from_role" name="from_role"
                                    data-placeholder="From Role" data-dropdown-parent="#modalFormDuplicate">
                                    <option value=""></option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group fv-row col-12">
                                <label class="form-label font-weight-normal mb-1">To Role</label>
                                <select class="form-control font-weight-normal" id="to_role" name="to_role"
                                    data-placeholder="To Role" data-dropdown-parent="#modalFormDuplicate">
                                    <option value=""></option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group fv-row col-12">
                                <label class="form-label font-weight-normal mb-1">Except Access</label>
                                <select class="form-control font-weight-normal" id="exclude_accesses"
                                    name="exclude_accesses" data-placeholder="Except Access" multiple="multiple"
                                    data-dropdown-parent="#modalFormDuplicate">
                                    <option value=""></option>
                                    @foreach ($accesses as $access)
                                        <option value="{{ $access['code'] }}">{{ $access['name'] }}</option>
                                    @endforeach
                                </select>
                            </DIV>
                        </div>
                        <!--end::Row-->
                    </form>
                    <!--end::Form-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" form="formDuplicate" class="btn btn-default" data-dismiss="modal"
                        id="cancelDuplicate" name="cancelDuplicate"><i class="fa fa-times mr-2"></i> Cancel
                    </button>
                    <button type="submit" form="formDuplicate" class="btn btn-success" id="saveDuplicate"
                        name="saveDuplicate">
                        <span class="indicator-label"><i class="fas fa-save mr-2"></i> Save</span>
                        <span class="indicator-progress d-none">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Form Duplicate-->

    <!--begin::Modal - Form Duplicate-->
    <div class="modal" tabindex="-1" id="modalFormEdit" data-backdrop="static" data-keyboard="false"
        data-focus="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content position-absolute">
                <div class="modal-header align-items-center">
                    <h4 class="modal-title">Edit Access</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="formEdit" name="formEdit" class="form">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="form-group fv-row col-12">
                                <label class="form-label font-weight-normal mb-1">Access Name</label>
                                <input type="text" placeholder="Access" id="editAccess" name="editAccess"
                                    autocomplete="off" value="" class="form-control border-0" readonly>
                            </div>
                            <div class="form-group fv-row col-12">
                                <label class="form-label font-weight-normal mb-1">Permissions</label>
                                <div class="d-flex flex-nowrap" id="editAccessContainer">
                                </div>
                            </div>
                        </div>
                        <!--end::Row-->
                    </form>
                    <!--end::Form-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" form="formEdit" class="btn btn-default" data-dismiss="modal"
                        id="cancelEditAccess" name="cancelEditAccess"><i class="fa fa-times mr-2"></i> Cancel
                    </button>
                    <button type="button" form="formEdit" class="btn btn-success" id="saveEditAccess"
                        name="saveEditAccess">
                        <span class="indicator-label"><i class="fas fa-save mr-2"></i> Save</span>
                        <span class="indicator-progress d-none">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Form Duplicate-->

    <script>
        const _accesses = {!! empty($accesses) ? 'null' : json_encode($accesses) !!};
        const _roles = {!! empty($roles) ? 'null' : json_encode($roles) !!};
    </script>
</x-layouts.admin>
