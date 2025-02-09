<x-layouts.admin>
    <!-- Toolbar -->
    <section class="content-header-fixed pt-0 px-0">
        <nav class="navbar navbar-expand navbar-white shadow-sm">
            <!-- Left -->
            <ul class="navbar-nav">
                @can('doc-create')
                    <li class="nav-item mr-2">
                        <a href="{{ route('documents.create') }}" class="btn btn-sm btn-outline-success" id="create" name="create">
                            <i class="fas fa-plus d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">New</span>
                        </a>
                    </li>
                @endcan
                @can('doc-edit')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-warning" id="edit" name="edit">
                            <i class="fas fa-edit d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Edit</span>
                        </button>
                    </li>
                @endcan
                @can('doc-cancel')
                    <li class="nav-item mr-2">
                        <button type="button" class="btn btn-sm btn-outline-danger" id="cancel" name="cancel">
                            <i class="fas fa-times d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">Cancel</span>
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
                        <table id="list_datatable" name="list_datatable" class="table table-hover table-sm display nowrap compact" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="align-middle text-center">#</th>
                                    <th rowspan="2" class="align-middle">Doc. No.</th>
                                    <th rowspan="2" class="align-middle text-center">Date</th>
                                    <th rowspan="2" class="align-middle">Category</th>
                                    <th rowspan="2" class="align-middle">Notes</th>
                                    <th rowspan="2" class="align-middle">Owner</th>
                                    <th rowspan="2" class="align-middle">Approval Type</th>
                                    <th colspan="3" class="text-center">Review</th>
                                    <th colspan="3" class="text-center">Acknowledge</th>
                                    <th rowspan="2" class="align-middle">Locked</th>
                                    <th rowspan="2" class="align-middle">Public</th>
                                    <th rowspan="2" class="align-middle text-center">Status</th>
                                </tr>
                                <tr>
                                    <th class="border-top-0">Required</th>
                                    <th class="border-top-0">Workflow</th>
                                    <th class="border-top-0">Reviewed</th>
                                    <th class="border-top-0">Required</th>
                                    <th class="border-top-0">Workflow</th>
                                    <th class="border-top-0">Acknowledged</th>
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

    <!--begin::Modal - Form Filter-->
    <div class="modal" tabindex="-1" id="modalFormFilter" data-backdrop="static" data-keyboard="false" data-focus="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Approval Workflow Type</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_approval_workflow_type" name="filter_filter_approval_workflow_typeapproval_type" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                        @foreach ($workflowTypes as $workflowType)
                                            <option value="{{ $workflowType->value }}">{{ $workflowType->getLabel() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Category</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_category" name="filter_category" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Sub Category</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_category_sub" name="filter_category_sub" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Review Workflow Type</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_review_workflow_type" name="filter_review_workflow_type" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                        @foreach ($workflowTypes as $workflowType)
                                            <option value="{{ $workflowType->value }}">{{ $workflowType->getLabel() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Review Required</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_is_review_required" name="filter_is_review_required" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Is Review</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_is_reviewed" name="filter_is_reviewed" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Acknowledgement Workflow Type</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_acknowledgement_workflow_type" name="filter_acknowledgement_workflow_type" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                        @foreach ($workflowTypes as $workflowType)
                                            <option value="{{ $workflowType->value }}">{{ $workflowType->getLabel() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Acknowledgement Required</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_is_acknowledgement_required" name="filter_is_acknowledgement_required" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-weight-normal mb-1">Is Acknowledged</label>
                                    <select class="form-control font-weight-normal form-select2" id="filter_is_acknowledged" name="filter_is_acknowledged" data-hide-search="true" data-dropdown-parent="#modalFormFilter">
                                        <option value=""></option>
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-normal mb-1">Is Locked</label>
                                <select class="form-control font-weight-normal form-select2" id="filter_is_locked" name="filter_is_locked" data-dropdown-parent="#modalFormFilter" data-hide-search="true">
                                    <option value=""></option>
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-normal mb-1">For Public</label>
                                <select class="form-control font-weight-normal form-select2" id="filter_is_public" name="filter_is_public" data-dropdown-parent="#modalFormFilter" data-hide-search="true">
                                    <option value=""></option>
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-normal mb-1">Status</label>
                                <select class="form-control font-weight-normal form-select2" id="filter_status" name="filter_status" data-dropdown-parent="#modalFormFilter" multiple="multiple">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}">{{ $status->getLabel() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-normal mb-1">Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="filter_date" name="filter_date">
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
</x-layouts.admin>
