<?php

namespace App\Http\Controllers\DMS;

use App\Enums\DocumentStatus;
use App\Enums\WorkflowType;
use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DMS\DocumentCreateRequest;
use App\Models\Basic\Category;
use App\Services\DocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MyDocumentContoller extends Controller
{
    protected DocumentService $service;

    public function __construct(DocumentService $service)
    {
        $this->service = $service;
    }

    public function index(): View
    {
        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/dms/my-document/list.js'
        ]);

        // workflow type
        $workflowTypes = WorkflowType::cases();

        // document status
        $statuses = DocumentStatus::cases();

        // category
        $categories = Category::where('is_active', true)->get(['id', 'name']);

        return view('dms.my-document.list')->with(compact('workflowTypes', 'statuses', 'categories'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $queries = DB::table('documents', 'd')
            ->leftJoin("category_subs AS cs", "d.category_sub_id", "=", "cs.id")
            ->leftJoin("categories AS c", "cs.category_id", "=", "c.id")
            ->leftJoin("users AS u", "d.owner_id", "=", "u.id")
            ->selectRaw("d.id, d.doc_no, d.date, d.due_date, c.name AS category_name, cs.name AS category_sub_name, u.name AS owner_name, d.approval_workflow_type,
                        d.is_locked, d.req_review, d.is_reviewed, d.req_acknowledgement, d.is_acknowledged, d.status")
            ->when($request->filled('date_start') && $request->filled('date_end'), function ($query) use ($request) {
                // handle date
                $startDate = Date::parse($request->get('date_start'))->format('Y-m-d');
                $endDate = Date::parse($request->get('date_end'))->format('Y-m-d');

                return $query->whereBetween('d.date', [$startDate, $endDate]);
            })
            ->when($request->filled('is_locked'), function ($query) use ($request) {
                return $query->where('d.is_locked', filter_var($request->get('is_locked'), FILTER_VALIDATE_BOOLEAN));
            })
            ->when($request->filled('req_review'), function ($query) use ($request) {
                return $query->where('d.req_review', filter_var($request->get('req_review'), FILTER_VALIDATE_BOOLEAN));
            })
            ->when($request->filled('req_acknowledge'), function ($query) use ($request) {
                return $query->where('d.req_acknowledgement', filter_var($request->get('req_acknowledge'), FILTER_VALIDATE_BOOLEAN));
            })
            ->when($request->filled('approval_workflow_type'), function ($query) use ($request) {
                return $query->where('d.approval_workflow_type', $request->get('approval_workflow_type'));
            })
            ->when($request->filled('is_reviewed'), function ($query) use ($request) {
                return $query->where('d.is_reviewed', filter_var($request->get('is_reviewed'), FILTER_VALIDATE_BOOLEAN));
            })
            ->when($request->filled('is_acknowledged'), function ($query) use ($request) {
                return $query->where('d.is_acknowledged', filter_var($request->get('is_acknowledged'), FILTER_VALIDATE_BOOLEAN));
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                return $query->where('cs.category_id', $request->get('category'));
            })
            ->when($request->filled('category_sub'), function ($query) use ($request) {
                return $query->where('d.category_sub_id', $request->get('category_sub'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                return $query->whereIn('d.status', $request->get('status'));
            });

        return DataTables::query($queries)
            ->editColumn('approval_workflow_type', function ($data) {
                return WorkflowType::tryFrom($data->approval_workflow_type)->getLabel();
            })
            ->editColumn('status', function ($data) {
                return '<span class="badge badge-' . DocumentStatus::tryFrom($data->status)->getBadge() . '">' . DocumentStatus::tryFrom($data->status)->getLabel() . '</span>';
            })->rawColumns(['status'])->toJson();
    }

    public function create(): View
    {
        // vendor js
        GeneralHelper::addAdditionalVendorJS([
            url('assets/vendor/plugins/tinymce/tinymce.min.js'),
            url('assets/vendor/plugins/sortable/Sortable.min.js'),
            url('assets/vendor/plugins/sortable/jquery-sortable.min.js'),
        ]);

        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/dms/my-document/create.js'
        ]);

        // approval type
        $workflowTypes = WorkflowType::cases();

        // category
        $categories = Category::where('is_active', true)->get(['id', 'name']);

        return view('dms.my-document.create')->with(compact('workflowTypes', 'categories'));
    }

    public function store(DocumentCreateRequest $request): JsonResponse
    {
        // validated request
        $validated = $request->validated();

        $data = $this->service->store(
            docDate: now(Session::get('timezone')),
            dueDate: !empty($validated['due_date']) ? Date::parse($validated['due_date']) : null,
            categorySubId: $validated['category_sub'],
            ownerId: Auth::id(),
            notes: $validated['notes'],
            approvalWorkflowType: WorkflowType::tryFrom($validated['approval_workflow_type']),
            isLocked: $validated['is_locked'],
            reviewWorkflowType: WorkflowType::tryFrom($validated['approval_workflow_type']),
            reqReview: $validated['req_review'],
            acknowledgementWorkflowType: WorkflowType::INORDER,
            reqAcknowledgement: $validated['req_acknowledgement'],
            files: $validated['files'],
            approvalUsers: $validated['approval_users'],
        );

        return response()->json(['message' => "Your document number : {$data->doc_no} successfully created."])->setStatusCode(Response::HTTP_CREATED);
    }
}
