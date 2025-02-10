<x-layouts.admin>
    <!-- Toolbar -->
    <section class="content-header-fixed pt-0 px-0">
        <nav class="navbar navbar-expand navbar-white shadow-sm">
            <!-- Left -->
            <ul class="navbar-nav">
                <li class="nav-item mr-2">
                    <a href="{{ route('documents.list') }}" class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-arrow-left d-inline"></i>
                        <span class="ml-2 d-none d-sm-inline font-weight-bold">Back</span>
                    </a>
                </li>
                <div class="border-left text-center mx-2">&nbsp;</div>
                @if ($data->status->isEditable())
                    @can('doc-edit')
                        <li class="nav-item mr-2">
                            <a href="{{ route('documents.edit', ['id' => $data->id]) }}" class="btn btn-sm btn-outline-warning" id="edit" name="edit">
                                <i class="fas fa-edit d-inline"></i>
                                <span class="ml-2 d-none d-sm-inline font-weight-bold">Edit</span>
                            </a>
                        </li>
                    @endcan
                @endif
                @can('doc-create')
                    <li class="nav-item mr-2">
                        <button href="{{ route('documents.create') }}" class="btn btn-sm btn-outline-info" id="createWithRef" name="createWithRef">
                            <i class="fas fa-hand-pointer d-inline"></i>
                            <span class="ml-2 d-none d-sm-inline font-weight-bold">New - With Ref Doc</span>
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
                <div class="row">
                    <div class="col-md-3">
                        <!-- general information -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h5 class="card-title">General Information</h5>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body px-3 py-1">
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item border-top-0">
                                        <strong>Doc. No.</strong>
                                        <span class="float-right">{{ $data->doc_no }}</span>
                                    </li>
                                    <li class="list-group-item border-top-0">
                                        <strong>Status</strong>
                                        <span class="float-right">
                                            <span class="badge badge-{{ $data->status->getBadge() }}">{{ $data->status->getLabel() }}</span>
                                        </span>
                                    </li>
                                    <li class="list-group-item border-top-0">
                                        <strong>Date</strong>
                                        <span class="float-right">{{ app()->general->dateFormat($data->date) }}</span>
                                    </li>
                                    @if (!empty($data->due_date))
                                        <li class="list-group-item border-top-0">
                                            <strong>Due Date</strong>
                                            <span class="float-right">{{ app()->general->dateFormat($data->due_date) }}</span>
                                        </li>
                                    @endif
                                    <li class="list-group-item border-top-0">
                                        <strong>Owner</strong>
                                        <span class="float-right">{{ $data->owner_name }}</span>
                                    </li>
                                    <li class="list-group-item border-top-0">
                                        <strong>Category</strong>
                                        <span class="float-right">{{ $data->category_name }}</span>
                                    </li>
                                    <li class="list-group-item border-top-0">
                                        <strong>Sub Category</strong>
                                        <span class="float-right">{{ $data->category_sub_name }}</span>
                                    </li>
                                    <li class="list-group-item border-top-0">
                                        <strong>Public</strong>
                                        <span class="float-right">
                                            @if ($data->is_public)
                                                <span class="badge badge-warning">Yes</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- ./ general information -->

                        <!-- approval workflow -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h5 class="card-title">Approval Workflow</h5>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body py-1">
                                <ul class="list-group list-group-unbordered mb-3">
                                    @foreach ($data->approval_users as $user)
                                        <li class="list-group-item border-top-0">
                                            <div class="d-flex align-items-center">
                                                <h4 class="mr-5">#{{ $user->order }}</h4>
                                                <div class="d-flex flex-grow-1 justify-content-between align-items-center">
                                                    <div class="d-flex flex-column">
                                                        <span class="font-weight-bold mb-1">{{ $user->name }}</span>
                                                        <span class="mb-1">{{ $user->email }}</span>
                                                    </div>
                                                    @if ($user->is_approved)
                                                        <i class="fas fas fa-check-circle text-success"></i>
                                                    @else
                                                        <i class="fas fas fa-hourglass-half text-secondary"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- ./ approval workflow -->

                        @if ($data->is_review_required)
                            <!-- review workflow -->
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h5 class="card-title">Review Workflow</h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body py-1">
                                    <ul class="list-group list-group-unbordered mb-3">
                                        @foreach ($data->review_users as $user)
                                            <li class="list-group-item border-top-0">
                                                <div class="d-flex align-items-center">
                                                    <h4 class="mr-5">#{{ $user->order }}</h4>
                                                    <div class="d-flex flex-grow-1 justify-content-between align-items-center">
                                                        <div class="d-flex flex-column">
                                                            <span class="font-weight-bold mb-1">{{ $user->name }}</span>
                                                            <span class="mb-1">{{ $user->email }}</span>
                                                        </div>
                                                        @if ($user->is_reviewed)
                                                            <i class="fas fas fa-check-circle text-success"></i>
                                                        @else
                                                            <i class="fas fas fa-hourglass-half text-secondary"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- ./ review workflow -->
                        @endif

                        @if ($data->is_acknowledgement_required)
                            <!-- acknowledge workflow -->
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h5 class="card-title">Acknowledge Workflow</h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body py-1">
                                    <ul class="list-group list-group-unbordered mb-3">
                                        @foreach ($data->acknowledge_users as $user)
                                            <li class="list-group-item border-top-0">
                                                <div class="d-flex align-items-center">
                                                    <h4 class="mr-5">#{{ $user->order }}</h4>
                                                    <div class="d-flex flex-grow-1 justify-content-between align-items-center">
                                                        <div class="d-flex flex-column">
                                                            <span class="font-weight-bold mb-1">{{ $user->name }}</span>
                                                            <span class="mb-1">{{ $user->email }}</span>
                                                        </div>
                                                        @if ($user->is_acknowledged)
                                                            <i class="fas fas fa-check-circle text-success"></i>
                                                        @else
                                                            <i class="fas fas fa-hourglass-half text-secondary"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- ./ acknowledge workflow -->
                        @endif

                        @if ($data->informed_users->isNotEmpty())
                            <!-- informed users -->
                            <div class="card card-gray">
                                <div class="card-header">
                                    <h5 class="card-title">Informed Users</h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body py-1">
                                    <ul class="list-group list-group-unbordered mb-3">
                                        @foreach ($data->informed_users as $user)
                                            <li class="list-group-item border-top-0">
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex flex-grow-1 justify-content-between align-items-center">
                                                        <div class="d-flex flex-column">
                                                            <span class="font-weight-bold mb-1">{{ $user->name }}</span>
                                                            <span class="mb-1">{{ $user->email }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- ./ informed users -->
                        @endif

                    </div>
                    <div class="col-md-9">
                        <div class="card card-success">
                            <div class="card-header">
                                <h5 class="card-title">Document Files ({{ $data->files->count() }})</h5>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="d-flex flex-grow-1 flex-nowrap scrollbar-x bg-white shadow-sm rounded p-3">
                                    @foreach ($data->files as $file)
                                        <div class="px-2">
                                            <x-cards.file :id="$file->id" :file-origin-name="$file->file_origin_name" :file-name="$file->file_name" :file-size="$file->file_size" :file-ext="$file->file_ext" :file-type="$file->file_type" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- loading -->
                        <div class="loading-preview-file d-none">
                            <div class="d-flex flex-grow-1 justify-content-center p-5">
                                <span class="indicator-progress text-center">
                                    <span class="spinner-border spinner-border-md text-primary mb-2" aria-hidden="true"></span>
                                    <p>Loading...</p>
                                </span>
                            </div>
                        </div>
                        <!-- ./ loading -->

                        <div class="card" id="filePreviewContainer">
                            <div class="card-body p-0" id="filePreview">
                                <div class="d-flex flex-grow-1 justify-content-center p-5">
                                    Click file name to preview
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
    </section>
    <!-- /.content -->
</x-layouts.admin>
