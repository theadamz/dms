<x-layouts.admin>
    <!-- Toolbar -->
    <section class="content-header-fixed pt-0 px-0">
        <nav class="navbar navbar-expand navbar-white shadow-sm">
            <!-- Left -->
            <ul class="navbar-nav">
                <li class="nav-item mr-2">
                    <a href="{{ route('documents.list') }}" class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-list d-inline"></i>
                        <span class="ml-2 d-none d-sm-inline font-weight-bold">List</span>
                    </a>
                </li>
                <div class="border-left text-center mx-2">&nbsp;</div>
                <li class="nav-item mr-2">
                    <button type="button" class="btn btn-sm btn-outline-info" id="clear" name="clear">
                        <i class="fas fa-times d-inline"></i>
                        <span class="ml-2 d-none d-sm-inline font-weight-bold">Clear</span>
                    </button>
                </li>
            </ul>

            <!-- Right -->
            <ul class="navbar-nav ml-auto">
                @can('my-doc-create')
                    <li class="nav-item mr-2">
                        <button type="submit" form="formInput" class="btn btn-success" id="save" name="save">
                            <span class="indicator-label"><i class="fas fa-save mr-2"></i> Submit</span>
                            <span class="indicator-progress d-none">
                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            </span>
                        </button>
                    </li>
                @endcan
            </ul>
        </nav>
    </section>

    <!-- Main content -->
    <section class="content">
        <section class="flex-column-fluid">
            <div class="container-fluid">
                <form id="formInput" name="formInput" class="form" method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <!-- general information -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">General Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <x-inputs.textbox label="Owner" element-name="owner_name" addClass="ignore" :readonly="true" value="{{ session('name') }}" />
                                        </div>
                                        <div class="col-md-3">
                                            <x-inputs.textbox label="Date" element-name="date" addClass="ignore" :readonly="true" value="{{ app()->general->dateFormat(now()) }}" />
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Due Date <span class="text-info">*</span></label>
                                                <div class="input-group date" id="due_date" data-target-input="[name='due_date']">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="checkbox" id="use_due_date" name="use_due_date" data-target="#due_date" data-toggle="tooltip" data-placement="top" title="Check to use due date">
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#due_date" name="due_date" value="{{ now()->format(config('setting.local.backend_date_format')) }}" disabled />
                                                    <div class="input-group-append" data-target="#due_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Approval Workflow Type <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="approval_workflow_type" name="approval_workflow_type" data-hide-search="true" data-allow-clear="false">
                                                    @foreach ($workflowTypes as $workflowType)
                                                        <option value="{{ $workflowType->value }}">{{ $workflowType->getLabel() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Category <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="category" name="category" data-allow-clear="false">
                                                    <option value=""></option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Sub Category <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="category_sub" name="category_sub" data-allow-clear="false">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Review Required <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="req_review" name="req_review" data-hide-search="true" data-allow-clear="false">
                                                    <option value="false">No</option>
                                                    <option value="true">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Acknowledgement Required <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="req_acknowledgement" name="req_acknowledgement" data-hide-search="true" data-allow-clear="false">
                                                    <option value="false">No</option>
                                                    <option value="true">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Notes </label>
                                                <textarea id="notes" name="notes" class="form-control font-weight-normal" placeholder="Notes"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Lock After Submit <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="is_locked" name="is_locked" data-hide-search="true" data-allow-clear="false">
                                                    <option value="false">No</option>
                                                    <option value="true">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./ general information -->
                        </div>
                        <div class="col-md-4">
                            <!-- document files -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Document Files <span class="text-danger">*</span></h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body py-3 px-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-sm" id="addFiles" name="addFiles">Add Files</button>
                                            <input type="file" id="files" name="files" class="d-none ignore" multiple accept="{{ '.' . join(', .', array_merge(config('setting.other.file_doc_attachment_allowed'), config('setting.other.file_img_allowed'))) }}" />
                                        </div>
                                        <div class="col-md-12 border-top my-3"></div>
                                        <div class="col-md-12" id="documentFilesContainer">
                                            <!-- list of files -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./ document files -->

                            <!-- approval workflow -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Approval Workflow <span class="text-danger">*</span></h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body py-3 px-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-sm" id="addApprovalUsers" name="addApprovalUsers" data-toggle="modal" data-target="#modalFormUser">Add Users</button>
                                            <button type="button" class="btn btn-info btn-sm" id="addApprovalSet" name="addApprovalSet" data-toggle="modal" data-target="#modalFormApprovalSet">Add Approval Set</button>
                                        </div>
                                        <div class="col-md-12 border-top my-3"></div>
                                        <div class="col-md-12">
                                            <div class="list-group col" id="approvalWorkflowContainer">
                                                <!-- list of users with order -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./ approval workflow -->
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.container-fluid -->
        </section>
    </section>
    <!-- /.content -->

    <!--begin::Modal - Form User-->
    <div class="modal" tabindex="-1" id="modalFormUser" data-backdrop="static" data-keyboard="false" data-focus="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content position-absolute">
                <div class="modal-header align-items-center">
                    <h4 class="modal-title">Add Users Workflow</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="formUser" name="formUser" class="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Select one or more users <span class="text-danger">*</span></label>
                                    <select class="form-control font-weight-normal form-select2" id="users" name="users" data-allow-clear="false" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" form="formUser" class="btn btn-default" data-dismiss="modal" id="cancelUser" name="cancelUser">Cancel</button>
                    <button type="submit" form="formUser" class="btn btn-primary" id="addUser" name="addUser">
                        <span class="indicator-label"> Add</span>
                        <span class="indicator-progress d-none">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Form User-->

    <!--begin::Modal - Form Approval Set-->
    <div class="modal" tabindex="-1" id="modalFormApprovalSet" data-backdrop="static" data-keyboard="false" data-focus="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content position-absolute">
                <div class="modal-header align-items-center">
                    <h4 class="modal-title">Add Approval Set</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="formApprovalSet" name="formApprovalSet" class="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Select approval set <span class="text-danger">*</span></label>
                                    <select class="form-control font-weight-normal form-select2" id="approval_set" name="approval_set" data-allow-clear="false">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" form="formApprovalSet" class="btn btn-default" data-dismiss="modal" id="cancelApprovalSetUsers" name="cancelApprovalSetUsers">Cancel</button>
                    <button type="submit" form="formApprovalSet" class="btn btn-primary" id="addApprovalSetUsers" name="addApprovalSetUsers">
                        <span class="indicator-label"> Add</span>
                        <span class="indicator-progress d-none">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Form Approval Set-->

    <script>
        const cancelURL = "{{ route('documents.list') }}";
    </script>
</x-layouts.admin>
