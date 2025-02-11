<x-layouts.admin>
    <!-- Toolbar -->
    <section class="content-header-fixed pt-0 px-0">
        <nav class="navbar navbar-expand navbar-white shadow-sm">
            <!-- Left -->
            <ul class="navbar-nav">
                @can('my-doc-list-access')
                    <li class="nav-item mr-2">
                        <a href="{{ route('documents.list') }}" class="btn btn-sm btn-outline-dark">
                            <i class="fas fa-list d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">List</span>
                        </a>
                    </li>
                @endcan
                <div class="border-left text-center mx-2">&nbsp;</div>
                <li class="nav-item mr-2">
                    <button type="button" class="btn btn-sm btn-outline-info" id="reload" name="reload">
                        <i class="fas fa-sync-alt d-inline"></i>
                        <span class="ml-2 d-none d-sm-inline font-weight-bold">Reload</span>
                    </button>
                </li>
            </ul>

            <!-- Right -->
            <ul class="navbar-nav ml-auto">
                @can('doc-edit')
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
                <form id="formInput" name="formInput" class="form" method="POST" action="{{ route('documents.update', ['id' => $data->id]) }}" enctype="multipart/form-data">
                    @method('put')
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
                                        <div class="col-md-4">
                                            <x-inputs.textbox label="Owner" element-name="owner_name" addClass="ignore" :readonly="true" value="{{ $data->owner_name }} - {{ $data->department_name }}" />
                                        </div>
                                        <div class="col-md-4">
                                            <x-inputs.textbox label="Date" element-name="date" addClass="ignore" :readonly="true" value="{{ empty($data->date) ? '' : app()->general->dateFormat($data->date) }}" />
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Due Date <span class="text-info">*</span></label>
                                                <div class="input-group date" id="due_date" data-target-input="[name='due_date']">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="checkbox" id="use_due_date" name="use_due_date" data-target="#due_date" data-toggle="tooltip" data-placement="top" title="Check to use due date" {{ !empty($data->due_date) ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#due_date" name="due_date" value="{{ empty($data->date) ? '' : app()->general->dateFormat($data->date) }}" {{ !empty($data->due_date) ? '' : 'disabled' }} />
                                                    <div class="input-group-append" data-target="#due_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Category <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="category" name="category" data-allow-clear="false">
                                                    <option value=""></option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"{{ $data->category_id === $category->id ? ' selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Sub Category <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="category_sub" name="category_sub" data-allow-clear="false">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Lock After Submit <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="is_locked" name="is_locked" data-hide-search="true" data-allow-clear="false">
                                                    <option value="false"{{ !$data->is_locked ? ' selected' : '' }}>No</option>
                                                    <option value="true"{{ $data->is_locked ? ' selected' : '' }}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Notes </label>
                                                <textarea id="notes" name="notes" class="form-control font-weight-normal" placeholder="Notes" maxlength="255" rows="3">{{ $data->notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group fv-row">
                                                <label class="form-label font-weight-normal mb-1">Public <span class="text-danger">*</span></label>
                                                <select class="form-control font-weight-normal form-select2" id="is_public" name="is_public" data-hide-search="true" data-allow-clear="false">
                                                    <option value="false"{{ !$data->is_public ? ' selected' : '' }}>No</option>
                                                    <option value="true"{{ $data->is_public ? ' selected' : '' }}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./ general information -->

                            <div class="row">
                                <div class="col-md-6">
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
                                                    <input type="file" id="attachments" name="attachments" class="d-none ignore" multiple accept="{{ '.' . join(', .', config('setting.other.file_doc_allowed')) }}" />
                                                </div>
                                                <div class="col-md-12 border-top my-3"></div>
                                                <div class="col-md-12" id="documentFilesContainer">
                                                    <!-- list of files -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ./ document files -->
                                </div>
                                <div class="col-md-6">
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
                                                <div class="form-group fv-row col-md-12">
                                                    <select class="form-control font-weight-normal form-select2" id="approval_workflow_type" name="approval_workflow_type" data-placeholder="Workflow Type" data-hide-search="true" data-allow-clear="false">
                                                        <option value=""></option>
                                                        @foreach ($workflowTypes as $workflowType)
                                                            <option value="{{ $workflowType->value }}"{{ $data->approval_workflow_type === $workflowType ? ' selected' : '' }}>{{ $workflowType->getLabel() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3 mb-md-0">
                                                    <button type="button" class="btn btn-primary btn-sm btn-block" id="addApprovalUsers" name="addApprovalUsers">Add Users</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-info btn-sm btn-block" id="addApprovalUsersSet" name="addApprovalUsersSet">Add Approval Set</button>
                                                </div>
                                                <div class="col-md-12 border-top my-3"></div>
                                                <div class="col-md-12">
                                                    <div class="list-group" id="approvalWorkflowContainer">
                                                        <!-- list of users with order -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ./ approval workflow -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- informed user -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Informed Users</h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body py-3 px-3">
                                    <div class="row">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <button type="button" class="btn btn-primary btn-sm btn-block" id="addInformedUsers" name="addInformedUsers">Add Users</button>
                                        </div>
                                        <div class="col-md-12 border-top my-3"></div>
                                        <div class="col-md-12">
                                            <div class="list-group" id="informedUsersContainer">
                                                <!-- list of users with order -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./ informed user -->

                            <!-- review workflow -->
                            <button type="button" class="btn btn-secondary btn-block mb-3 {{ $data->is_review_required ? 'd-none' : '' }}" id="addReviewWorkFlow" name="addReviewWorkFlow">Add Review Workflow</button>
                            <div class="{{ $data->is_review_required ? '' : 'd-none' }}" id="reviewWorkflowCard">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Review Workflow</h5>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" id="removeReviewWorkFlow">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body py-3 px-3">
                                        <div class="row">
                                            <div class="form-group fv-row col-md-12">
                                                <select class="form-control font-weight-normal form-select2" id="review_workflow_type" name="review_workflow_type" data-placeholder="Workflow Type" data-hide-search="true" data-allow-clear="false">
                                                    <option value=""></option>
                                                    @foreach ($workflowTypes as $workflowType)
                                                        <option value="{{ $workflowType->value }}"{{ $data->review_workflow_type === $workflowType ? ' selected' : '' }}>{{ $workflowType->getLabel() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <button type="button" class="btn btn-primary btn-sm btn-block" id="addReviewUsers" name="addReviewUsers">Add Users</button>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-info btn-sm btn-block" id="addReviewApprovalSet" name="addReviewApprovalSet">Add Approval Set</button>
                                            </div>
                                            <div class="col-md-12 border-top my-3"></div>
                                            <div class="col-md-12">
                                                <div class="list-group" id="reviewWorkflowContainer">
                                                    <!-- list of users with order -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./ review workflow -->

                            <!-- acknowledgement workflow -->
                            <button type="button" class="btn btn-secondary btn-block mb-3 {{ $data->is_acknowledgement_required ? 'd-none' : '' }}" id="addAcknowledgementWorkFlow" name="addAcknowledgementWorkFlow">Add Acknowledgement Workflow</button>
                            <div class="{{ $data->is_acknowledgement_required ? '' : 'd-none' }}" id="acknowledgementWorkflowCard">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Acknowledgement Workflow</h5>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" id="removeAcknowledgementWorkFlow">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body py-3 px-3">
                                        <div class="row">
                                            <div class="form-group fv-row col-md-12">
                                                <select class="form-control font-weight-normal form-select2" id="acknowledgement_workflow_type" name="acknowledgement_workflow_type" data-placeholder="Workflow Type" data-hide-search="true" data-allow-clear="false">
                                                    <option value=""></option>
                                                    @foreach ($workflowTypes as $workflowType)
                                                        <option value="{{ $workflowType->value }}"{{ $data->is_acknowledgement_required && $data->acknowledgement_workflow_type === $workflowType ? ' selected' : '' }}>{{ $workflowType->getLabel() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <button type="button" class="btn btn-primary btn-sm btn-block" id="addAcknowledgementUsers" name="addAcknowledgementUsers">Add Users</button>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-info btn-sm btn-block" id="addAcknowledgementApprovalSet" name="addAcknowledgementApprovalSet">Add Approval Set</button>
                                            </div>
                                            <div class="col-md-12 border-top my-3"></div>
                                            <div class="col-md-12">
                                                <div class="list-group" id="acknowledgementWorkflowContainer">
                                                    <!-- list of users with order -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ./ acknowledgement workflow -->
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.container-fluid -->
        </section>
    </section>
    <!-- /.content -->

    <!--begin::Modal - Form User-->
    <div class="modal" tabindex="-1" id="modalFormWorkflowUser" data-backdrop="static" data-keyboard="false" data-focus="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content position-absolute">
                <div class="modal-header align-items-center">
                    <h4 class="modal-title">Add Users</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="formWorkflowUser" name="formWorkflowUser" class="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Select one or more users <span class="text-danger">*</span></label>
                                    <select class="form-control font-weight-normal form-select2" id="workflowUsers" name="workflowUsers" data-allow-clear="false" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" form="formWorkflowUser" class="btn btn-default" data-dismiss="modal" id="workflowUserscancel" name="workflowUserscancel">Cancel</button>
                    <button type="submit" form="formWorkflowUser" class="btn btn-primary" id="workflowUsersAdd" name="workflowUsersAdd">
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
    <div class="modal" tabindex="-1" id="modalFormWorkflowUserSet" data-backdrop="static" data-keyboard="false" data-focus="false">
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
                    <form id="formWorkflowUserSet" name="formWorkflowUserSet" class="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group fv-row">
                                    <label class="form-label font-weight-normal mb-1">Select approval set <span class="text-danger">*</span></label>
                                    <select class="form-control font-weight-normal form-select2" id="workflowUsersSet" name="workflowUsersSet" data-allow-clear="false">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" form="formWorkflowUserSet" class="btn btn-default" data-dismiss="modal" id="workflowUsersSetCancel" name="workflowUsersSetCancel">Cancel</button>
                    <button type="submit" form="formWorkflowUserSet" class="btn btn-primary" id="workflowUsersSetAdd" name="workflowUsersSetAdd">
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
        const categorySubId = "{{ $data->category_sub_id }}";

        // for inputs
        let refDocId = "{{ $data->ref_doc_id }}";
        let documentFiles = {{ Js::from($data->files) }};
        let approvalUsers = {{ Js::from($data->approval_users) }};
        let informedUsers = {{ Js::from($data->informed_users) }};
        let isReviewRequired = {{ $data->is_review_required ? 'true' : 'false' }};
        let reviewUsers = {{ Js::from($data->review_users ?? []) }};
        let isAcknowledgementRequired = {{ $data->is_acknowledgement_required ? 'true' : 'false' }};
        let acknowledgementUsers = {{ Js::from($data->acknowledge_users ?? []) }};
    </script>
</x-layouts.admin>
