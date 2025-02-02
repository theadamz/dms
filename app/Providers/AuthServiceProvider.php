<?php

namespace App\Providers;

use App\Services\AccessService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // get service
        $service = app(AccessService::class);

        // define gates
        $this->roleAccess($service);
        $this->userAccess($service);
    }

    private function roleAccess(AccessService $service): void
    {
        $roleList = config('access.roleList');

        // loop $roleList
        foreach ($roleList as $access) {
            // get permissions
            $permissions = $access['permissions'];

            // loop $permissions
            foreach ($permissions as $permission) {
                Gate::define($access['code'], function () use ($access, $service) {
                    return $service->isRoleHasAccess(Session::get('role_id'), $access['code']);
                });

                Gate::define($access['code'] . '-' . $permission, function () use ($access, $permission, $service) {
                    return $service->isRoleAccessAllowed(Session::get('role_id'), $access['code'], $permission);
                });
            }
        }
    }

    private function userAccess(AccessService $service): void
    {
        $roleList = config('access.userList');

        // loop $roleList
        foreach ($roleList as $access) {
            // get permissions
            $permissions = $access['permissions'];

            // loop $permissions
            foreach ($permissions as $permission) {
                Gate::define($access['code'], function () use ($access, $service) {
                    return $service->isUserHasAccess(Auth::id(), $access['code']);
                });

                Gate::define($access['code'] . '-' . $permission, function () use ($access, $permission, $service) {
                    return $service->isUserAccessAllowed(Auth::id(), $access['code'], $permission);
                });
            }
        }
    }
}
