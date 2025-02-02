<?php

namespace App\Http\Controllers\Config;

use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Config\ForgotPasswordRequest;
use App\Http\Requests\Config\ResetPasswordRequest;
use App\Http\Requests\Config\SignUpRequest;
use App\Http\Requests\Config\UserCreateRequest;
use App\Http\Requests\Config\UserUpdateRequest;
use App\Models\Config\Role;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Vite;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(): View
    {
        // js
        GeneralHelper::addAdditionalJS([
            'resources/js/pages/config/user.js'
        ]);

        // get roles
        $roles = Role::all();

        // get timezone
        $timezones = GeneralHelper::getTimezone();

        return view('config.user', compact('roles', 'timezones'));
    }

    public function indexRegister(): View
    {
        // js
        $additionalJS = [
            'resources/js/pages/account/sign-up.js'
        ];

        // get timezone
        $timezones = GeneralHelper::getTimezone();

        return view('account.sign-up')->with(compact('additionalJS', 'timezones'));
    }

    public function indexVerificationEmailNotice(): View
    {
        // js
        $additionalJS = [
            'resources/js/pages/account/verification-notice.js'
        ];

        return view('account.verification-notice')->with(compact('additionalJS'));
    }

    public function indexForgotPassword(): View
    {
        // js
        $additionalJS = [
            'resources/js/pages/account/forgot-password.js'
        ];

        return view('account.forgot-password')->with(compact('additionalJS'));
    }

    public function indexResetPassword(Request $request, string $token): View
    {
        // js
        $additionalJS = [
            'resources/js/pages/account/reset-password.js'
        ];

        $email = $request->get('email');

        return view('account.reset-password')->with(compact('additionalJS', 'token', 'email'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $queries = DB::table("users", "u")->leftJoin("roles AS r", "r.id", "=", "u.role_id")
            ->selectRaw("u.id, u.username, u.email, u.name, r.name AS role_name, u.timezone, u.is_active")
            ->when($request->filled('is_active'), function ($query) use ($request) {
                return $query->where('u.is_active', filter_var($request->get('is_active'), FILTER_VALIDATE_BOOLEAN));
            })->when($request->filled('role'), function ($query) use ($request) {
                return $query->where('u.role_id', $request->get('role'));
            })->when(!in_array(session('user_id'), (array) config('access.userIdExceptions')), function ($query) {
                return $query->whereNotIn('u.id', config('access.userIdExceptions'));
            });

        return DataTables::query($queries)->toJson();
    }

    public function store(UserCreateRequest $request): JsonResponse
    {
        // validate request
        $validated = $request->validated();

        // check for duplicate
        $exist = User::where(function (Builder $query) use ($validated) {
            $query->whereRaw('LOWER(email)=?', [str($validated['email'])->lower()])->orWhereRaw('LOWER(username)=?', [str($validated['username'])->lower()]);
        })->exists();

        // if exist then
        if ($exist) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username or email already exist."
                    ],
                    "email" => [
                        "username or email already exist."
                    ],
                ],
                "message" => Response::$statusTexts[Response::HTTP_CONFLICT],
            ], Response::HTTP_CONFLICT));
        }

        // save
        $user = new User($validated);
        $user->role_id = $validated['role'];
        $user->email_verified_at = now();

        // if picture exist in request
        if ($request->hasFile('picture')) {
            // save file with hash filename
            $newFileName = $validated['picture']->hashName();
            $validated['picture']->storeAs(config('setting.other.path_to_upload'), $newFileName);

            // set column value
            $user->picture = $newFileName;
        }

        $user->save();

        return response()->json(["message" => "User successfully created."])->setStatusCode(Response::HTTP_CREATED);
    }

    public function storeRegister(SignUpRequest $request): RedirectResponse
    {
        // validate request
        $validated = $request->validated();

        // check duplicate
        if (User::whereRaw('LOWER(email)=?', [str($validated['email'])->lower()])->exists()) {
            return back()->withErrors([
                "email" => [
                    "Email already used."
                ],
            ])->withInput();
        }

        // get role
        $role = Role::whereRaw('LOWER(code)=?', ['user'])->first();

        // save
        $user = new User($validated);
        $user->username = $validated['email'];
        $user->role_id = $role->id;
        $user->save();

        // send email verification
        event(new Registered($user));

        // set session flash
        Session::flash('notification', [
            'type' => 'success',
            'icon' => 'fas fa-check',
            'title' => 'Sign Up Success',
            'message' => 'Verification link sent to ' . $validated['email'] . ', please check your mail.'
        ]);

        return redirect()->route('sign-in');
    }

    public function show(string $id): JsonResponse
    {
        // validate parameter
        $validated = Validator::make(['id' => $id], [
            'id' => ['required', "uuid", Rule::exists("users", 'id')],
        ])->validated();

        // get data
        $data = User::where('id', $validated['id'])->select(['username', 'email', 'name', 'role_id', 'timezone', 'is_active', 'picture'])->first();

        // if data empty
        if (empty($data)) {
            throw new HttpResponseException(response([
                "message" => "Data not found.",
            ], Response::HTTP_NOT_FOUND));
        }

        // refactor
        if (!empty($data->picture)) {
            $data->picture = asset('/contents/' . $data->picture);
        }

        return response()->json(['message' => Response::$statusTexts[Response::HTTP_OK], 'data' => $data])->setStatusCode(Response::HTTP_OK);
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        // validasi request
        $validated = $request->validated();

        // cek duplikat
        $user = User::where(function (Builder $query) use ($validated) {
            $query->whereRaw('LOWER(email)=?', [str($validated['email'])->lower()])->orWhereRaw('LOWER(username)=?', [str($validated['username'])->lower()]);
        })->where('id', '!=', $validated['id']);
        if ($user->count() > 0) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username or email already exist."
                    ],
                    "email" => [
                        "username or email already exist."
                    ],
                ],
                "message" => Response::$statusTexts[Response::HTTP_CONFLICT],
            ], Response::HTTP_CONFLICT));
        }

        // if password filled then add it to the validated
        if ($request->filled('password')) {
            $validated['password'] = $validated['password'];
        } else {
            unset($validated['password']);
        }

        // get old data
        $prev = User::where('id', $validated['id'])->first();

        // save changes
        $data = User::find($validated['id']);
        $data->fill($validated);
        $data->role_id = $validated['role'];

        // if picture exist in request
        if ($request->hasFile('picture')) {
            // save file with hash filename
            $newFileName = $validated['picture']->hashName();
            $validated['picture']->storeAs(config('setting.other.path_to_upload'), $newFileName);

            // set column value
            $data->picture = $newFileName;
        }

        // if no_picture is true then delete picture
        if (!empty($prev->picture) && $validated['no_picture']) {
            $data->picture = null;
        }

        $data->save();

        // delete old picture
        if ((!empty($prev->picture) && $request->hasFile('picture')) || (!empty($prev->picture) && $validated['no_picture'])) {
            Storage::delete(config('setting.other.path_to_upload') . '/' . $prev->picture);
        }

        return response()->json(["message" => "User successfully saved."])->setStatusCode(Response::HTTP_OK);
    }

    public function updateRegisterVerification(EmailVerificationRequest $request): RedirectResponse
    {
        // update email_verified_at in table user
        $request->fulfill();

        return redirect()->route('sign-in');
    }

    public function updateForgotPassword(ForgotPasswordRequest $request): RedirectResponse
    {
        // validate request
        $validated = $request->validated();

        // check duplicate
        if (!User::whereRaw('LOWER(email)=?', [str($validated['email'])->lower()])->exists()) {
            return back()->withErrors([
                "email" => [
                    "Email not found."
                ],
            ])->withInput();
        }

        // process reset password
        $status = Password::sendResetLink(['email' => $validated['email']]);

        // if status reset link sent
        if ($status === Password::RESET_LINK_SENT) {
            // set session flash
            Session::flash('message', 'Reset link sent to ' . $validated['email'] . ', please check your mail to continue.');

            return redirect()->route('password.request');
        } else {
            back()->withErrors(['email' => __($status)])->withInput();
        }
    }

    public function updateWithNewPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(str()->random(60));

                $user->save();

                // send email password reset
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('sign-in')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function destroy(Request $request): JsonResponse
    {
        // handle input
        $ids = str($request->post('id'))->isJson() ? ['ids' => json_decode($request->post('id'), true)] : ['ids' => $request->post('id')];

        // validate request
        $validated = Validator::make($ids, [
            'ids' => ['required', "array"],
            'ids.*' => ['required', "uuid", Rule::exists('users', 'id')],
        ])->validated();

        try {
            // set is_active = false
            User::whereIn('id', $validated['ids'])->update([
                'is_active' => false
            ]);

            return response()->json(["message" => count($validated['ids']) . " user(s) successfully disabled."])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            // throw error
            throw new HttpResponseException(response([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public function lov(Request $request): View
    {
        // modal
        $data['title'] = 'Users List';
        $data['srcURL'] = url('/dt/configs/users');
        $data['initSearch'] = $request->get('search');
        $data['queryParameters'] = Arr::query(['is_active' => $request->get('is_active')]);

        // datatable
        $data['columnHeaders'] = ['#', 'Username', 'Email', 'Name'];
        $data['columns'] = [
            ['data' => 'id', 'orderable' => false, 'visible' => false],
            ['data' => 'username', 'orderable' => true],
            ['data' => 'email', 'orderable' => true],
            ['data' => 'name', 'orderable' => true],
        ];
        $data['columnDefinitions'] = [];
        $data['columnOrders'] = [];
        $data['jsFile'] = Vite::asset('resources/js/pages/lov/common.js');

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

        // query
        $queries = User::select('id', 'name', 'username', 'email')
            ->where(function ($query) use ($request) {
                $query->orWhere('name', 'ilike', "%{$request->keyword}%")->orWhere('email', 'ilike', "%{$request->keyword}%")->orWhere('username', 'ilike', "%{$request->keyword}%");
            })->where('is_active', true)->orderBy('id');

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

    public function resendVerificationLink(Request $request): RedirectResponse
    {
        // resend link verification email
        $request->user()->sendEmailVerificationNotification();

        // set session flash
        Session::flash('notification', [
            'type' => 'success',
            'icon' => 'fas fa-check',
            'title' => 'Sign Up Success',
            'message' => 'Verification link sent, please check your mail.'
        ]);

        return redirect()->route('verification.notice');
    }
}
