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
                <div class="card">
                    <div class="card-body">
                        test
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
