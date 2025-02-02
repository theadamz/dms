<?php

namespace App\Http\Controllers\Basic;

use App;
use App\Helpers\GeneralHelper;
use App\Helpers\OpenSpoutHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Basic\AprovalSetRequest;
use App\Models\Basic\ApprovalSet;
use App\Models\Basic\ApprovalSetUser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApprovalSetController extends Controller
{
    protected string $templateName = "template_approval_sets.xlsx";

    public function index(): View
    {
        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/basic/approval-set.js'
        ]);

        // js vendor
        GeneralHelper::addAdditionalVendorJS([
            url('assets/vendor/plugins/sortable/Sortable.min.js'),
            url('assets/vendor/plugins/sortable/jquery-sortable.min.js'),
        ]);

        // template name
        $templateName = $this->templateName;

        return view('basic.approval-set')->with(compact('templateName'));
    }

    public function datatable(): JsonResponse
    {
        $queries = ApprovalSet::selectRaw("id, name, count, created_by, created_at, updated_by, updated_at, last_used")
            ->with(['created_user:id,name', 'updated_user:id,name']);

        return DataTables::eloquent($queries)->toJson();
    }

    public function store(AprovalSetRequest $request): JsonResponse
    {
        // validate request
        $validated = $request->validated();

        // check for duplicate
        if (ApprovalSet::whereRaw('LOWER(name)=?', [str($validated['name'])->lower()])->exists()) {
            throw new HttpResponseException(response([
                "errors" => [
                    "name" => [
                        "name already exist."
                    ],
                ],
                "message" => Response::$statusTexts[Response::HTTP_CONFLICT],
            ], Response::HTTP_CONFLICT));
        }

        try {
            // begin trans
            DB::beginTransaction();

            // create approval set
            $data = new ApprovalSet($validated);
            $data->save();

            // create approval set users
            $count = 0;
            foreach ($validated['users'] as $user) {
                ApprovalSetUser::create([
                    'approval_set_id' => $data->id,
                    'user_id' => $user['id'],
                    'order' => $user['order'],
                ]);

                $count++;
            }

            // update count
            $data->count = $count;
            $data->save();

            // commit changes
            DB::commit();

            return response()->json(["message" => "Data successfully created."])->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // rollback
            DB::rollBack();

            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public function show(string $id): JsonResponse
    {
        // validate parameter
        $validated = Validator::make(['id' => $id], [
            'id' => ['required', "uuid", Rule::exists("approval_sets", 'id')],
        ])->validated();

        // get data
        $approvalSet = ApprovalSet::where('id', $validated['id'])->select(['name'])->first();

        // if data empty
        if (empty($approvalSet)) {
            throw new HttpResponseException(response([
                "message" => "Data not found.",
            ], Response::HTTP_NOT_FOUND));
        }

        // refactor
        $data = [
            'name' => $approvalSet->name,
            'users' => ApprovalSetUser::where('approval_set_id', $validated['id'])->with(['user:id,name,email'])->orderBy('order')->get()->map(function ($item) {
                return [
                    'id' => $item->user_id,
                    'name' => $item->user->name,
                    'email' => $item->user->email,
                    'order' => $item->order,
                ];
            }),
        ];

        return response()->json(['message' => Response::$statusTexts[Response::HTTP_OK], 'data' => $data])->setStatusCode(Response::HTTP_OK);
    }

    public function update(AprovalSetRequest $request): JsonResponse
    {
        // validasi request
        $validated = $request->validated();

        // cek duplikat
        if (ApprovalSet::whereRaw('LOWER(name)=?', [str($validated['name'])->lower()])->where('id', '!=', $validated['id'])->exists()) {
            throw new HttpResponseException(response([
                "errors" => [
                    "name" => [
                        "name already exist."
                    ],
                ],
                "message" => Response::$statusTexts[Response::HTTP_CONFLICT],
            ], Response::HTTP_CONFLICT));
        }

        try {
            // begin trans
            DB::beginTransaction();

            // save changes
            $data = ApprovalSet::find($validated['id']);
            $data->fill($validated);
            $data->save();

            // get previous users
            $previousUsers = ApprovalSetUser::where('approval_set_id', $data->id)->pluck('user_id');

            /// collect new users
            $newUsers = collect($validated['users'])->pluck('id');

            // get previous users that not in new users and delete it if exist
            $deletedUsers = $previousUsers->diff($newUsers);
            if ($deletedUsers->isNotEmpty()) {
                // delete approval set users
                ApprovalSetUser::where('approval_set_id', $data->id)->whereIn('user_id', $deletedUsers)->delete();
            }

            // create approval set users
            foreach ($validated['users'] as $user) {
                // if not exist in previous users then create
                if (!$previousUsers->contains($user['id'])) {
                    ApprovalSetUser::create([
                        'approval_set_id' => $data->id,
                        'user_id' => $user['id'],
                        'order' => $user['order'],
                    ]);
                } else {
                    ApprovalSetUser::where('approval_set_id', $data->id)->where('user_id', $user['id'])->update(['order' => $user['order']]);
                }
            }

            // update count
            $data->count = ApprovalSetUser::where('approval_set_id', $data->id)->count();
            $data->save();

            // commit changes
            DB::commit();

            return response()->json(["message" => "Data successfully saved."])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            // rollback
            DB::rollBack();

            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        // handle input
        $ids = str($request->post('id'))->isJson() ? ['ids' => json_decode($request->post('id'), true)] : ['ids' => $request->post('id')];

        // validate request
        $validated = Validator::make($ids, [
            'ids' => ['required', "array"],
            'ids.*' => ['required', "uuid", Rule::exists('approval_sets', 'id')],
        ])->validated();

        try {
            // set is_active = false
            ApprovalSet::whereIn('id', $validated['ids'])->delete();

            return response()->json(["message" => count($validated['ids']) . " data successfully deleted."])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public function export(OpenSpoutHelper $openSpout): JsonResponse
    {
        // create query
        $query = DB::table('approval_sets', 'ast')
            ->leftJoin('approval_set_users AS asu', 'asu.approval_set_id', '=', 'ast.id')
            ->leftJoin('users AS u', 'u.id', '=', 'asu.user_id')
            ->selectRaw("ast.name, u.name AS user_name, asu.order")
            ->orderByRaw('ast.name, asu.order');

        // get data
        $records = $query->get()->toArray();

        // variables
        $fileName = now()->format('YmdHis') . "_basics_approval_sets.xlsx";
        $url = route('download-temp-file', ['fileNameEncoded' => base64_encode($fileName)]);

        // columns header
        $columns = [
            'no' => ['text' => 'No.', 'type' => 'serial', 'align' => 'left'],
            'name' => ['text' => 'Approval Set Name', 'type' => 'string', 'align' => 'left'],
            'user_name' => ['text' => 'User Name', 'type' => 'string', 'align' => 'left'],
            'order' => ['text' => 'Order', 'type' => 'int', 'align' => 'center'],
        ];

        $openSpout->generateXlsx(
            filePath: config('setting.other.path_to_temp') . '/' . $fileName,
            columns: $columns,
            records: $records,
            useNumberFirstRow: true,
        );

        return response()->json(["url" => $url])->setStatusCode(Response::HTTP_OK);
    }

    public function options(Request $request, ?string $approvalSetId = null): JsonResponse
    {
        // variables
        $usePaging = $request->has('page') || $request->has('lastId') ? true : false; // if query params has page/lstId then use paging
        $pagingType = $request->has('pageType') && in_array($request->get('pageType'), ['offset', 'id']) ? $request->get('pageType') : ($usePaging ? 'offset' : 'no_paging'); // paging type offset|id|no_paging, if $usePaging = true but no PageType then offset. default: no_paging
        $limit = $request->has('perPage') ? $request->get('perPage') : config('setting.page.default_limit', 10); // set limit, if not found then become 10
        $page = $pagingType === 'offset' && $request->has('page') ? filter_var($request->get('page'), FILTER_VALIDATE_INT) : 1; // set page if pageType is offset
        $lastId = $pagingType === 'id' && $request->has('lastId') ? $request->get('lastId') : null;

        // query
        if (empty($approvalSetId)) {
            $queries = ApprovalSet::select('id', 'name', 'count')
                ->where(function ($query) use ($request) {
                    $query->orWhere('name', 'ilike', "%{$request->keyword}%");
                })->orderBy('id');
        } else {
            $queries = DB::table("approval_set_users", "asu")->leftJoin("users AS u", "u.id", "=", "asu.user_id")
                ->where('asu.approval_set_id', $approvalSetId)
                ->where(function ($query) use ($request) {
                    $query->orWhere('u.name', 'ilike', "%{$request->keyword}%")->orWhere('u.email', 'ilike', "%{$request->keyword}%")->orWhere('u.username', 'ilike', "%{$request->keyword}%");
                })
                ->selectRaw("u.id, u.username, u.name, u.email, asu.order")
                ->orderBy('asu.order');
        }


        ############################# START PAGING #############################
        // paging offset
        if ($usePaging && $pagingType === 'offset') {
            $queries->skip($page === 1 ? 0 : (($page - 1) * $limit));
        }

        // filter paging id
        if ($usePaging && $pagingType === 'id') {
            if ($lastId === null) {
                $queries->whereRaw('id IS NOT NULL');
            } else {
                $queries->where('id', '>', $lastId);
            }
        }
        ############################# END PAGING #############################

        // set limit and get data
        $queries = $queries->limit($limit)->get();

        return response()->json(['data' => $queries, 'message' => Response::$statusTexts[Response::HTTP_OK]], Response::HTTP_OK);
    }
}
