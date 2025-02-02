<?php

namespace App\Http\Controllers\Config;

use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Config\AccessAddRequest;
use App\Http\Requests\Config\AccessDuplicateRequest;
use App\Http\Requests\Config\AccessUpdateRequest;
use App\Models\Config\Role;
use App\Models\Config\RoleAccess;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccessController extends Controller
{
    public function index(): View
    {
        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/config/access.js'
        ]);

        // ambil roles
        $roles = Role::all();

        // ambil access list
        $accesses = collect(config('access.roleList'))->toArray();

        return view('config.access', compact('roles', 'accesses'));
    }

    public function retriveRoleAccesses(RoleAccess $model, string $roleId): JsonResponse
    {
        // check if roleId empty
        if (empty($roleId)) {
            throw new HttpResponseException(response([
                "message" => "Data not found.",
            ], Response::HTTP_NOT_FOUND));
        }

        // get access
        $accesses = $model->where('role_id', $roleId)->orderBy('code')->get(['id', 'role_id', 'code', 'permission', 'is_allowed'])->toArray();

        // if access empty
        if (empty($accesses)) {
            return response()->json(['message' => 'Not found', 'data' => []])->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        // variables
        $data = [];
        $code = "";
        $prevAccess = [];
        $permissions = [];

        // loop
        foreach ($accesses as $idx => $access) {
            // if $idx = 0
            if ($idx === 0) {
                $code = $access['code'];
            }

            // if $code not empty or different with incoming data then push it to $data
            if (!empty($code) && $code !== $access['code']) {
                // push to $data
                $data[] = [
                    'code' => $prevAccess['code'],
                    'name' => collect(config('access.roleList'))->firstWhere('code', $prevAccess['code'])['name'] ?? null,
                    'permissions' => $permissions
                ];

                // clear $permission and fill $code
                $permissions = [];
                $code = $access['code'];
            }

            // push permission
            $permissions[$access['permission']] = boolval($access['is_allowed']);

            // set code
            $prevAccess = $access;
        }

        // push data terakhir ke $data
        $data[] = [
            'code' => $prevAccess['code'],
            'name' => collect(config('access.roleList'))->firstWhere('code', $prevAccess['code'])['name'],
            'permissions' => $permissions
        ];

        return response()->json(['message' => Response::$statusTexts[Response::HTTP_OK], 'data' => $data])->setStatusCode(Response::HTTP_OK);
    }

    public function store(AccessAddRequest $request)
    {
        // validate request
        $validated = $request->validated();

        // variable
        $inserted = 0;

        try {
            // begin trans
            DB::beginTransaction();

            // Looping access_lists
            foreach ($validated['access_lists'] as $accessCode) {
                // get access list by code
                $access = collect(config("access.roleList"))->firstWhere('code', $accessCode);

                // if empty then just skip it
                if (empty($access)) {
                    continue;
                }

                // check if access already exist for the role
                $isExist = RoleAccess::where([
                    ['role_id', '=', $validated['role']],
                    ['code', '=', $accessCode],
                    ['permission', '=', $access['permissions'][0]],
                ])->exists();

                // if access already exist then skip it
                if ($isExist) {
                    continue;
                }

                // create
                RoleAccess::create([
                    'role_id' => $validated['role'],
                    'code' => $accessCode,
                    'permission' => $access['permissions'][0],
                    'is_allowed' => true
                ]);

                $inserted++;
            }

            // commit changes
            DB::commit();

            return response()->json(["message" => $inserted . " access(es) created."])->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // rollback
            DB::rollBack();

            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public function show(RoleAccess $model, string $roleId, string $accessCode): JsonResponse
    {
        // check if $roleId or $accessCode empty
        if (empty($roleId) || empty($accessCode)) {
            throw new HttpResponseException(response([
                "message" => "Data not found.",
            ], Response::HTTP_NOT_FOUND));
        }

        // get access
        $accesses = $model->where('role_id', $roleId)->where('code', $accessCode)->get(['id', 'code', 'permission', 'is_allowed'])->toArray();

        // if access empty
        if (empty($accesses)) {
            return response()->json(['message' => 'Not found', 'data' => []])->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        // get access data from config
        $accessConfig = collect(config('access.roleList'))->firstWhere('code', $accessCode);

        // loop
        $permissions = [];
        $permissionsFromConfig = $accessConfig['permissions']; // get access data permissions values
        foreach ($accesses as $access) {
            // search key by parsing value access permission to permissionsFromConfig
            $key = array_search($access['permission'], $permissionsFromConfig);
            unset($permissionsFromConfig[$key]);

            // add permission and value is_allowed
            $permissions[$access['permission']] = boolval($access['is_allowed']);
        }

        // loop permissionFromConfig to add the rest of things
        foreach ($permissionsFromConfig as $permission) {
            // add permission and value to false
            $permissions[$permission] = false;
        }

        // data to respon
        $data = [
            'code' => $access['code'],
            'name' => $accessConfig['name'],
            'permissions' => $permissions
        ];

        return response()->json(['message' => Response::$statusTexts[Response::HTTP_OK], 'data' => $data])->setStatusCode(Response::HTTP_OK);
    }

    public function update(AccessUpdateRequest $request)
    {
        // validasi request
        $validated = $request->validated();

        try {
            // begin trans
            DB::beginTransaction();

            // Looping accesses
            foreach ($validated['permissions'] as $permission => $isAllowed) {
                // check if already exist
                $isExist = RoleAccess::where([
                    ['role_id', '=', $validated['role']],
                    ['code', '=', $validated['code']],
                    ['permission', '=', $permission],
                ])->exists();

                // model
                $model = RoleAccess::where([
                    ['role_id', '=', $validated['role']],
                    ['code', '=', $validated['code']],
                    ['permission', '=', $permission],
                ]);

                if ($isExist) {
                    $model->update([
                        'is_allowed' => $isAllowed
                    ]);
                } else {
                    $model->create([
                        'role_id' => $validated['role'],
                        'code' => $validated['code'],
                        'permission' => $permission,
                        'is_allowed' => $isAllowed
                    ]);
                }
            }

            // commit changes
            DB::commit();

            return response()->json(["message" => "Access successfully updated."])->setStatusCode(Response::HTTP_OK);
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
        $ids = str($request->post('id'))->isJson() ? json_decode($request->post('id'), true) : $request->post('id');

        // set data
        $data = [
            'role' => $request->post('role'),
            'ids' => $ids,
        ];

        // validate request
        $validated = Validator::make($data, [
            'role' => ['required', "uuid", Rule::exists('roles', 'id')],
            'ids' => ['required', "array"],
            'ids.*' => ['required', "alpha_dash", Rule::in(collect(config('access.roleList'))->pluck("code")->toArray())],
        ])->validated();

        try {

            // execute
            RoleAccess::whereIn('code', $validated['ids'])->where('role_id', $validated['role'])->delete();

            return response()->json(["message" => count($validated['ids']) . " access(es) successfully deleted."])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public function duplicate(AccessDuplicateRequest $request)
    {
        // validasi request
        $validated = $request->validated();

        // if role same
        if ($validated['from_role'] === $validated['to_role']) {
            throw new HttpResponseException(response([
                "message" => "Please select different role.",
            ], Response::HTTP_BAD_REQUEST));
        }

        // get access
        $accesses = RoleAccess::where("role_id", $validated['from_role'])->whereNotIn("code", $validated['exclude_accesses'])->get()->toArray();

        try {
            // begin trans
            DB::beginTransaction();

            // loop
            foreach ($accesses as $access) {
                // check if access already exist
                $isExist = RoleAccess::where([
                    ['role_id', '=', $validated['to_role']],
                    ['code', '=', $access['code']],
                    ['permission', '=', $access['permission']],
                ])->exists();

                // if already exist then skip
                if ($isExist) {
                    continue;
                }

                // buat data akses
                RoleAccess::create([
                    'role_id' => $validated['to_role'],
                    'code' => $access['code'],
                    'permission' => $access['permission'],
                    'is_allowed' => $access['is_allowed'],
                ]);
            }

            // commit changes
            DB::commit();

            return response()->json(['message' => 'Duplicate success.'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // rollback
            DB::rollBack();

            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }
}
