<?php

namespace App\Http\Controllers\Config;

use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Config\RoleRequest;
use App\Models\Config\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/config/role.js'
        ]);

        return view('config.role');
    }

    public function datatable(): JsonResponse
    {
        $queries = Role::select(['id', 'code', 'name', 'def_path'])
            ->when(!in_array(session('user_id'), (array) config('access.userIdExceptions')), function ($query) {
                return $query->whereNotIn('id', config('access.roleIdExceptions'));
            });

        return DataTables::eloquent($queries)->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        // validate request
        $validated = $request->validated();

        // check for duplicate
        $exist = Role::where(function (Builder $query) use ($validated) {
            $query->whereRaw('LOWER(code)=?', [str($validated['code'])->lower()]);
        })->exists();

        // if exist then
        if ($exist) {
            throw new HttpResponseException(response([
                "errors" => [
                    "code" => [
                        "code already exist."
                    ],

                ],
                "message" => Response::$statusTexts[Response::HTTP_CONFLICT],
            ], Response::HTTP_CONFLICT));
        }

        // save
        $data = new Role($validated);
        $data->save();

        return response()->json(["message" => "Data successfully created."])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // validate parameter
        $validated = Validator::make(['id' => $id], [
            'id' => ['required', "uuid", Rule::exists("roles", 'id')],
        ])->validated();

        // get data
        $data = Role::where('id', $validated['id'])->select(['code', 'name', 'def_path'])->first();

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
    public function update(RoleRequest $request, string $id)
    {
        // validate request
        $validated = $request->validated();

        // check for duplicate
        $exist = Role::where(function (Builder $query) use ($validated) {
            $query->whereRaw('LOWER(code)=?', [str($validated['code'])->lower()]);
        })->where('id', '!=', $validated['id'])->exists();

        // if exist then
        if ($exist) {
            throw new HttpResponseException(response([
                "errors" => [
                    "code" => [
                        "code already exist."
                    ],

                ],
                "message" => Response::$statusTexts[Response::HTTP_CONFLICT],
            ], Response::HTTP_CONFLICT));
        }

        // update
        $data = Role::find($validated['id']);
        $data->fill($validated);
        $data->save();

        return response()->json(["message" => "Role successfully saved."])->setStatusCode(Response::HTTP_OK);
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
            'ids.*' => ['required', "uuid", Rule::exists('roles', 'id')],
        ])->validated();

        try {
            Role::whereIn('id', $validated['ids'])->delete();

            return response()->json(["message" => count($validated['ids']) . " data successfully deleted."])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }
}
