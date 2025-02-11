<?php

namespace App\Http\Controllers\Basic;

use App\Helpers\GeneralHelper;
use App\Helpers\OpenSpoutHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Basic\CategoryRequest;
use App\Models\Basic\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    protected string $templateName = "template_categories.xlsx";

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/basic/category.js'
        ]);

        // template name
        $templateName = $this->templateName;

        return view('basic.category')->with(compact('templateName'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $queries = Category::selectRaw("id, name, is_active")
            ->when($request->filled('is_active'), function ($query) use ($request) {
                return $query->where('is_active', filter_var($request->get('is_active'), FILTER_VALIDATE_BOOLEAN));
            });

        return DataTables::eloquent($queries)->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        // validate request
        $validated = $request->validated();

        // check for duplicate
        $exist = Category::where(function (Builder $query) use ($validated) {
            $query->whereRaw('LOWER(name)=?', [str($validated['name'])->lower()]);
        })->exists();

        // if exist then
        if ($exist) {
            throw new HttpResponseException(response([
                "errors" => [
                    "name" => [
                        "name already exist."
                    ],

                ],
                "message" => Response::$statusTexts[Response::HTTP_CONFLICT],
            ], Response::HTTP_CONFLICT));
        }

        // save
        $data = new Category($validated);
        $data->save();

        return response()->json(["message" => "Data successfully created."])->setStatusCode(Response::HTTP_CREATED);
    }

    public function storeImport(Request $request, OpenSpoutHelper $openSpout): JsonResponse
    {
        // Prepare input
        $input = [
            'file' => $request->file('file')
        ];

        // Validate input
        $validated = Validator::make($input, [
            'file' => ["required", File::types(['xlsx'])->max(2048)],
        ])->validated();

        try {
            // handle file upload
            $file = $validated['file'];

            // save file with hash filename
            $file->storeAs(config('setting.other.path_to_temp'), $file->hashName());

            // construct the full path for reading the file
            $filePath = config('setting.other.path_to_temp') . '/' . $file->hashName();

            // Check if the file exists
            if (!Storage::disk('local')->exists($filePath)) {
                throw new HttpResponseException(response([
                    "message" => 'Uploaded file does not exist at the expected path.',
                ], Response::HTTP_NOT_FOUND));
            }

            // Read the Excel file
            $rows = $openSpout->readFileExcel(filePath: Storage::disk('local')->path($filePath), sheetName: "DATA", useFirstRowAsKeyName: true);

            // Delete the file after reading
            Storage::disk('local')->delete($filePath);

            // If rows are empty
            if (empty($rows)) {
                throw new HttpResponseException(response([
                    "message" => 'No detail found',
                ], Response::HTTP_NOT_FOUND));
            }

            // begin trans
            DB::beginTransaction();

            // variables
            $line = 1;
            $logs = "";
            $inserted = 0;

            // loop rows
            foreach ($rows as $key => $row) {
                // replace value
                $row['is_active'] = match ($row['is_active']) {
                    'Y' => true,
                    'N' => false,
                    default => null,
                };

                // input validator
                $validator = Validator::make($row, (new CategoryRequest())->rules());

                // if fails
                if ($validator->fails()) {
                    // add log
                    $logs .= "Line {$line}: " . PHP_EOL . GeneralHelper::prettyErrorMessage($validator->getMessageBag()) . PHP_EOL;

                    // increment
                    $line++;

                    // skip
                    continue;
                }

                // get validated data
                $validated = $validator->getData();

                // get data
                $isExist = Category::whereRaw("LOWER(name)=?", [str($validated['name'])->lower()])->exists();

                // if exist then skip
                if ($isExist) {
                    // add log
                    $logs .= "Line {$line}:" . PHP_EOL . "Name {$validated['name']} already exist." . PHP_EOL . PHP_EOL;

                    // increment
                    $line++;

                    // skip
                    continue;
                }

                // create new data
                Category::create([
                    'name' => $validated['name'],
                    'is_active' => $validated['is_active'],
                ]);

                // increment insert
                $inserted++;

                // add log
                $logs .= "Line {$line}:" . PHP_EOL . "inserted." . PHP_EOL . PHP_EOL;

                // increment
                $line++;
            }

            // commit changes
            DB::commit();

            if ($inserted > 0) {
                return response()->json(["message" => "{$inserted} data successfully imported.", "logs" => $logs])->setStatusCode(Response::HTTP_CREATED);
            } else {
                return response()->json(["message" => "No data imported.", "logs" => $logs])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            // rollback
            DB::rollBack();

            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        // validate parameter
        $validated = Validator::make(['id' => $id], [
            'id' => ['required', "uuid", Rule::exists("categories", 'id')],
        ])->validated();

        // get data
        $data = Category::where('id', $validated['id'])->select(['name', 'is_active'])->first();

        // if data empty
        if (empty($data)) {
            throw new HttpResponseException(response([
                "message" => "Data not found.",
            ], Response::HTTP_NOT_FOUND));
        }

        return response()->json(['message' => Response::$statusTexts[Response::HTTP_OK], 'data' => $data])->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request)
    {
        // validate request
        $validated = $request->validated();

        // check for duplicate
        $exist = Category::where(function (Builder $query) use ($validated) {
            $query->whereRaw('LOWER(name)=?', [str($validated['name'])->lower()]);
        })->where('id', '!=', $validated['id'])->exists();

        // if exist then
        if ($exist) {
            throw new HttpResponseException(response([
                "errors" => [
                    "name" => [
                        "name already exist."
                    ],
                ],
                "message" => Response::$statusTexts[Response::HTTP_CONFLICT],
            ], Response::HTTP_CONFLICT));
        }

        // update
        $data = Category::find($validated['id']);
        $data->fill($validated);
        $data->save();

        return response()->json(["message" => "Data successfully saved."])->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // handle input
        $ids = str($request->post('id'))->isJson() ? ['ids' => json_decode($request->post('id'), true)] : ['ids' => $request->post('id')];

        // validate request
        $validated = Validator::make($ids, [
            'ids' => ['required', "array"],
            'ids.*' => ['required', "uuid", Rule::exists('categories', 'id')],
        ])->validated();

        try {
            Category::whereIn('id', $validated['ids'])->delete();

            return response()->json(["message" => count($validated['ids']) . " data successfully deleted."])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public function export(Request $request, OpenSpoutHelper $openSpout): JsonResponse
    {
        // prepare input
        $input = [
            'is_active' => empty($request->post('is_active')) ? null : filter_var($request->post('is_active'), FILTER_VALIDATE_BOOLEAN),
        ];

        // validate input
        $validated  = Validator::make($input, [
            "is_active" => ["nullable", "boolean"],
        ])->validated();

        // create query
        $query = Category::select(['name', 'is_active'])
            ->when(!is_null($validated['is_active']), function ($query) use ($validated) {
                return $query->where('is_active', filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN));
            });

        // get data
        $records = $query->get()->toArray();

        // refactor data
        $records = array_map(function ($record) {
            // convert to array
            $record = (array) $record;

            // replace value
            $record['is_active'] = $record['is_active'] ? 'Yes' : 'No';

            return $record;
        }, $records);

        // variables
        $fileName = now()->format('YmdHis') . "_basics_categories.xlsx";
        $fileNameWithPath = config('setting.other.path_to_temp') . '/' . $fileName;

        // columns header
        $columns = [
            'no' => ['text' => 'No.', 'type' => 'serial', 'align' => 'left'],
            'name' => ['text' => 'Name', 'type' => 'string', 'align' => 'left'],
            'is_active' => ['text' => 'Active', 'type' => 'string', 'align' => 'left'],
        ];

        $openSpout->generateXlsx(
            filePath: Storage::disk('local')->path($fileNameWithPath),
            columns: $columns,
            records: $records,
            useNumberFirstRow: true,
        );

        // generate url
        $url = route('download-temp-file', ['fileNameEncoded' => base64_encode($fileName)]);

        return response()->json(["url" => $url])->setStatusCode(Response::HTTP_OK);
    }

    public function lov(Request $request): View
    {
        // modal
        $data['title'] = 'Category List';
        $data['srcURL'] = route('dt.basics.categories');
        $data['initSearch'] = $request->get('search');
        $data['queryParameters'] = Arr::query(['is_active' => $request->get('is_active') ?? true]);

        // datatable
        $data['columnHeaders'] = ['#', 'Name'];
        $data['columns'] = [
            ['data' => 'id', 'orderable' => false, 'visible' => false],
            ['data' => 'name', 'orderable' => true],
        ];
        $data['columnDefinitions'] = [];
        $data['columnOrders'] = [];
        $data['jsFile'] = 'resources/js/pages/lov/common.js';

        return view('lov.common')->with(compact('data'));
    }

    public function options(Request $request): JsonResponse
    {
        // variables
        $usePaging = $request->has('page') || $request->has('lastId') ? true : false; // if query params has page/lstId then use paging
        $pagingType = $request->has('pageType') && in_array($request->get('pageType'), ['offset', 'id']) ? $request->get('pageType') : ($usePaging ? 'offset' : 'no_paging'); // paging type offset|id|no_paging, if $usePaging = true but no PageType then offset. default: no_paging
        $limit = $request->has('perPage') ? $request->get('perPage') : config('setting.page.default_limit', 10); // set limit, if not found then become 10
        $page = $pagingType === 'offset' && $request->has('page') ? filter_var($request->get('page'), FILTER_VALIDATE_INT) : 1; // set page if pageType is offset
        $lastId = $pagingType === 'id' && $request->has('lastId') ? $request->get('lastId') : null;
        $filterIsActive = $request->has('is_active') && $request->filled('is_active') ? filter_var($request->get('is_active'), FILTER_VALIDATE_BOOLEAN) : true;

        // query
        $queries = Category::select('id', 'name')
            ->where(function ($query) use ($request) {
                $query->orWhere('name', 'ilike', "%{$request->keyword}%");
            })->where('is_active', $filterIsActive)->orderBy('id');

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
