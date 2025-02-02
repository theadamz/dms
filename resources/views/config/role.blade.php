<x-layouts.admin>
    <!-- Toolbar -->
    <section class="content-header-fixed pt-0 px-0">
        <nav class="navbar navbar-expand navbar-white shadow-sm">
            <!-- Left -->
            <ul class="navbar-nav">
                @can('config-role-create')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-success" id="create" name="create"
                                data-toggle="modal" data-target="#modalFormInput">
                            <i class="fas fa-plus d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Create</span>
                        </button>
                    </li>
                @endcan
                @can('config-role-edit')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-warning" id="edit" name="edit">
                            <i class="fas fa-edit d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Edit</span>
                        </button>
                    </li>
                @endcan
                @can('config-role-delete')
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
                <li class="nav-item mr-2">
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
                        <table id="list_datatable" name="list_datatable" class="table display" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Def. Path</th>
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
    <div class="modal" tabindex="-1" id="modalFormInput" data-backdrop="static" data-keyboard="false" data-focus="false" aria-hidden="true">
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
                                    <label class="form-label font-weight-normal mb-1">Code <span class="text-danger">*</span></label>
                                    <input type="text" id="code" name="code" class="form-control font-weight-normal" placeholder="Code" value="" maxlength="10" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Name <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control font-weight-normal" placeholder="Name" value="" maxlength="50" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Default Path <span class="text-danger">*</span></label>
                                    <input type="text" id="def_path" name="def_path" class="form-control font-weight-normal" placeholder="Default Path" value="" maxlength="255" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" form="formInput" class="btn btn-default" data-dismiss="modal" id="cancel"
                            name="cancel"><i class="fa fa-times mr-2"></i> Cancel
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
</x-layouts.admin>
