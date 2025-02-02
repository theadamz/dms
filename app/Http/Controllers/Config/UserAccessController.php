<?php

namespace App\Http\Controllers\Config;

use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Config\UserAccessAddRequest;
use App\Http\Requests\Config\UserAccessDuplicateRequest;
use App\Http\Requests\Config\UserAccessUpdateRequest;
use App\Models\Config\UserAccess;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserAccessController extends Controller
{
    public function index()
    {
        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/config/user-access.js'
        ]);

        // ambil access list
        $accesses = collect(config('access.userList'))->toArray();

        return view('config.user-access', compact('accesses'));
    }

    public function store(UserAccessAddRequest $request)
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
                $access = collect(config("access.userList"))->firstWhere('code', $accessCode);

                // if empty then just skip it
                if (empty($access)) {
                    continue;
                }

                // check if access already exist for the user
                $isExist = UserAccess::where([
                    ['user_id', '=', $validated['user']],
                    ['code', '=', $accessCode],
                    ['permission', '=', $access['permissions'][0]],
                ])->exists();

                // if access already exist then skip it
                if ($isExist) {
                    continue;
                }

                // create
                UserAccess::create([
                    'user_id' => $validated['user'],
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

    public function retriveUserAccesses(UserAccess $model, string $userId): JsonResponse
    {
        // check if userId empty
        if (empty($userId)) {
            throw new HttpResponseException(response([
                "message" => "Data not found.",
            ], Response::HTTP_NOT_FOUND));
        }

        // get access
        $accesses = $model->where('user_id', $userId)->orderBy('code')->get(['id', 'user_id', 'code', 'permission', 'is_allowed'])->toArray();

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
                    'name' => collect(config('access.userList'))->firstWhere('code', $prevAccess['code'])['name'] ?? null,
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
            'name' => collect(config('access.userList'))->firstWhere('code', $prevAccess['code'])['name'],
            'permissions' => $permissions
        ];

        return response()->json(['message' => Response::$statusTexts[Response::HTTP_OK], 'data' => $data])->setStatusCode(Response::HTTP_OK);
    }

    public function show(UserAccess $model, string $userId, string $accessCode): JsonResponse
    {
        // check if $userId or $accessCode empty
        if (empty($userId) || empty($accessCode)) {
            throw new HttpResponseException(response([
                "message" => "Data not found.",
            ], Response::HTTP_NOT_FOUND));
        }

        // get access
        $accesses = $model->where('user_id', $userId)->where('code', $accessCode)->get(['id', 'code', 'permission', 'is_allowed'])->toArray();

        // if access empty
        if (empty($accesses)) {
            return response()->json(['message' => 'Not found', 'data' => []])->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        // get access data from config
        $accessConfig = collect(config('access.userList'))->firstWhere('code', $accessCode);

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

    public function update(UserAccessUpdateRequest $request)
    {
        // validasi request
        $validated = $request->validated();

        try {
            // begin trans
            DB::beginTransaction();

            // Looping accesses
            foreach ($validated['permissions'] as $permission => $isAllowed) {
                // check if already exist
                $isExist = UserAccess::where([
                    ['user_id', '=', $validated['user']],
                    ['code', '=', $validated['code']],
                    ['permission', '=', $permission],
                ])->exists();

                // model
                $model = UserAccess::where([
                    ['user_id', '=', $validated['user']],
                    ['code', '=', $validated['code']],
                    ['permission', '=', $permission],
                ]);

                if ($isExist) {
                    $model->update([
                        'is_allowed' => $isAllowed
                    ]);
                } else {
                    $model->create([
                        'user_id' => $validated['user'],
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
            'user' => $request->post('user'),
            'ids' => $ids,
        ];

        // validate request
        $validated = Validator::make($data, [
            'user' => ['required', "uuid", Rule::exists('users', 'id')],
            'ids' => ['required', "array"],
            'ids.*' => ['required', "alpha_dash", Rule::in(collect(config('access.userList'))->pluck("code")->toArray())],
        ])->validated();

        try {

            // execute
            UserAccess::whereIn('code', $validated['ids'])->where('user_id', $validated['user'])->delete();

            return response()->json(["message" => count($validated['ids']) . " access(es) successfully deleted."])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public function duplicate(UserAccessDuplicateRequest $request)
    {
        // validasi request
        $validated = $request->validated();

        // if user same
        if ($validated['from_user'] === $validated['to_user']) {
            throw new HttpResponseException(response([
                "message" => "Please select different user.",
            ], Response::HTTP_BAD_REQUEST));
        }

        // get access
        $accesses = UserAccess::where("user_id", $validated['from_user'])->whereNotIn("code", $validated['exclude_accesses'])->get()->toArray();

        try {
            // begin trans
            DB::beginTransaction();

            // loop
            foreach ($accesses as $access) {
                // check if access already exist
                $isExist = UserAccess::where([
                    ['user_id', '=', $validated['to_user']],
                    ['code', '=', $access['code']],
                    ['permission', '=', $access['permission']],
                ])->exists();

                // if already exist then skip
                if ($isExist) {
                    continue;
                }

                // buat data akses
                UserAccess::create([
                    'user_id' => $validated['to_user'],
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
