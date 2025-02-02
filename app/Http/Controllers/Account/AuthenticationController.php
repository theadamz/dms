<?php

namespace App\Http\Controllers\Account;

use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\SignInRequest;
use App\Models\Config\Role;
use App\Models\Config\SignInHistory;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;

class AuthenticationController extends Controller
{
    public function index(): View|RedirectResponse
    {
        // if user already sign in then redirect to their default path
        if (Auth::check()) {
            return redirect()->intended(Session::get('def_path'));
        }

        // js tambahan
        $additionalJS = [
            'resources/js/app.js',
            'resources/js/pages/account/sign-in.js'
        ];

        return view('account.sign-in', compact('additionalJS'));
    }

    public function signIn(SignInRequest $request): RedirectResponse
    {
        // get from validated request
        $validated = $request->validated();

        // credential with email
        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_active' => true
        ];

        // credential with username
        $credentialsUsername = [
            'username' => $validated['email'],
            'password' => $validated['password'],
            'is_active' => true
        ];

        // if login failed
        if (!Auth::attempt($credentials, $request->has('remember')) && !Auth::attempt($credentialsUsername, $request->has('remember'))) {
            return back()->withErrors([
                "message" => "Invalid credentials.",
                "email" => [
                    "Invalid credentials."
                ],
            ])->withInput();
        }

        // get user
        $user = Auth::user();

        // set login
        Auth::login($user);

        // regenerate session
        $request->session()->regenerate();

        // get user
        $user = Auth::user();

        // get role
        $role = Role::where('id', $user->role_id)->first();

        // save user info in session
        Session::put('name', $user->name);
        Session::put('email', $user->email);
        Session::put('role_id', $user->role_id);
        Session::put('def_path', $role->def_path);
        Session::put('timezone', $user->timezone);
        Session::put('picture', !empty($user->picture) ? asset('/contents/' . $user->picture) : url('/assets/images/_photo_profile_blank.png'));

        // create sign in history
        $this->createSignInHistory();

        // check if user already verified email
        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->intended($role->def_path);
    }

    private function createSignInHistory(): void
    {
        // init request
        $request = app(Request::class);

        // get info ip address and location
        $data = GeneralHelper::getIpInfo();

        // variables
        $ip = empty($data) ? $request->getClientIp() : $data->ip;
        $os = (new Agent)->getUserAgent();
        $platform = (new Agent)->platform();
        $browser = (new Agent)->browser();
        $country = empty($data) ? null : $data->country;
        $city = empty($data) ? null : $data->city;

        // create history sign in
        SignInHistory::create([
            'ip' => $ip,
            'os' => $os,
            'platform' => $platform,
            'browser' => $browser,
            'country' => $country,
            'city' => $city,
            'user_id' => Auth::id(),
            'created_at' => now()
        ]);

        // update last login
        User::where('id', Auth::id())->update([
            'last_login_at' => now()
        ]);
    }

    public function signOut(Request $request): RedirectResponse
    {
        // clear cache
        Cache::forget(Session::get('role_id'));

        // logout and invalid session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // redirect
        return to_route('sign-in');
    }
}
