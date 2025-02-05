<?php

namespace App\Http\Controllers\Config;

use App\Exceptions\CustomException;
use App\Helpers\GeneralHelper;
use App\Helpers\OpenSpoutHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Config\DepartmentRequest;
use App\Models\Config\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    protected string $templateName = "template_departments.xlsx";

    public function index(): View
    {
        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/config/department.js'
        ]);

        // template name
        $templateName = $this->templateName;

        return view('config.department')->with(compact('templateName'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $queries = Department::selectRaw("id, name, is_active")
            ->when($request->filled('is_active'), function ($query) use ($request) {
                return $query->where('is_active', filter_var($request->get('is_active'), FILTER_VALIDATE_BOOLEAN));
            });

        return DataTables::eloquent($queries)->toJson();
    }

    public function store(DepartmentRequest $request): JsonResponse
    {
        // validate request
        $validated = $request->validated();

        // check for duplicate
        $exist = Department::where(function (Builder $query) use ($validated) {
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
        $data = new Department($validated);
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
                throw new CustomException(response([
                    "message" => 'Uploaded file does not exist at the expected path.',
                ], Response::HTTP_NOT_FOUND));
            }

            // Read the Excel file
            $rows = $openSpout->readFileExcel(filePath: Storage::disk('local')->path($filePath), sheetName: "DATA", useFirstRowAsKeyName: true);

            // Delete the file after reading
            Storage::disk('local')->delete($filePath);

            // If rows are empty
            if (empty($rows)) {
                throw new CustomException(response([
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
                $validator = Validator::make($row, (new DepartmentRequest())->rules());

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
                $isExist = Department::whereRaw("LOWER(name)=?", [str($validated['name'])->lower()])->exists();

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
                Department::create([
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

            // Custom exception
            if ($e instanceof CustomException) throw new HttpResponseException($e->getResponse());

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
            'id' => ['required', "uuid", Rule::exists("departments", 'id')],
        ])->validated();

        // get data
        $data = Department::where('id', $validated['id'])->select(['name', 'is_active'])->first();

        // if data empty
        if (empty($data)) {
            throw new HttpResponseException(response([
                "message" => "Data not found.",
            ], Response::HTTP_NOT_FOUND));
        }

        return response()->json(['message' => Response::$statusTexts[Response::HTTP_OK], 'data' => $data])->setStatusCode(Response::HTTP_OK);
    }

    public function update(DepartmentRequest $request)
    {
        // validate request
        $validated = $request->validated();

        // check for duplicate
        $exist = Department::where(function (Builder $query) use ($validated) {
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
        $data = Department::find($validated['id']);
        $data->fill($validated);
        $data->save();

        return response()->json(["message" => "Data successfully saved."])->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Request $request)
    {
        // handle input
        $ids = str($request->post('id'))->isJson() ? ['ids' => json_decode($request->post('id'), true)] : ['ids' => $request->post('id')];

        // validate request
        $validated = Validator::make($ids, [
            'ids' => ['required', "array"],
            'ids.*' => ['required', "uuid", Rule::exists('departments', 'id')],
        ])->validated();

        try {
            Department::whereIn('id', $validated['ids'])->delete();

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
        $query = Department::select(['name', 'is_active'])
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
        $fileName = now()->format('YmdHis') . "_configs_departments.xlsx";
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
}
