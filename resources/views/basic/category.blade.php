<x-layouts.admin>
    <!-- Toolbar -->
    <section class="content-header-fixed pt-0 px-0">
        <nav class="navbar navbar-expand navbar-white shadow-sm">
            <!-- Left -->
            <ul class="navbar-nav">
                @can('category-create')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-success" id="create" name="create" data-toggle="modal" data-target="#modalFormInput">
                            <i class="fas fa-plus d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Add</span>
                        </button>
                    </li>
                @endcan
                @can('category-edit')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-warning" id="edit" name="edit">
                            <i class="fas fa-edit d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Edit</span>
                        </button>
                    </li>
                @endcan
                @can('category-delete')
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
                @can('category-import')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-dark" id="import" name="import"
                                data-toggle="modal" data-target="#modalFormImport">
                            <i class="fas fa-file-import d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Import</span>
                        </button>
                    </li>
                @endcan
                @can('category-export')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-dark" id="export" name="export">
                            <i class="fas fa-file-export d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Export</span>
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
                                    <th>Name</th>
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
                                <x-inputs.textbox label="Name" :is-required="true" element-name="name" :max-length="50" />
                            </div>
                            <div class="col-md-12">
                                <x-inputs.checkbox label="Active" element-name="is_active" value="true" />
                            </div>
                        </div>
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

    @can('category-import')
        <!--begin::Modal - Form Import-->
        <div class="modal" tabindex="-1" id="modalFormImport" data-backdrop="static" data-keyboard="false"
             data-focus="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content position-absolute">
                    <div class="modal-header align-items-center">
                        <h4 class="modal-title">Import</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!--begin::Form-->
                        <form id="formImport" name="formImport" class="form">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group fv-row">
                                        <label class="form-label font-weight-normal mb-1">File <span
                                                  class="text-danger">*</span></label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file"
                                                   name="file" accept=".xlsx" />
                                            <label class="custom-file-label" for="file">Choose file</label>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Extensions : xlsx</small>
                                            <small class="text-muted">Max file size: 2MB </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label font-weight-normal mb-1">Logs</label>
                                        <textarea class="form-control font-weight-normal" rows="10" placeholder="Logs" id="logs" name="logs"
                                                  readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div class="d-flex">
                            <button type="button" form="formImport" class="btn btn-default mr-2" data-dismiss="modal"
                                    id="cancelUpload" name="cancelUpload"><i class="fa fa-times mr-2"></i> Close
                            </button>
                            <a href="{{ route('template.download', ['template_name' => $templateName]) }}" target="_blank"
                               class="btn btn-primary" id="template" name="template"><i
                                   class="fa fa-download mr-2"></i> Template
                            </a>
                        </div>
                        <button type="submit" form="formImport" class="btn btn-success" id="upload" name="upload"
                                formaction="javascript:;">
                            <span class="indicator-label"><i class="fas fa-upload mr-2"></i> Upload</span>
                            <span class="indicator-progress d-none">
                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Modal - Form Import-->
    @endcan

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
                                    <label class="form-label font-weight-normal mb-1">Active</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_is_active"
                                            name="filter_is_active" data-hide-search="true"
                                            data-dropdown-parent="#modalFormFilter">
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
                    <button type="reset" form="formFilter" class="btn btn-default" id="filterReset"
                            name="filterReset">
                        <i class="fas fa-times-circle mr-2"></i> Reset
                    </button>
                    <button type="submit" form="formFilter" class="btn btn-primary" id="filterApply"
                            name="filterApply">
                        <i class="fas fa-check mr-2"></i> Apply
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Form Filter-->
</x-layouts.admin>
