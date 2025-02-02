<?php

namespace App\Http\Middleware;

use App\Helpers\GeneralHelper;
use App\Services\AccessService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $accessCode = null, bool $checkAuthorization = true, bool $renderSideMenu = true): Response
    {
        // check if method is allowed
        if (!GeneralHelper::isMethodAllowed($request->method())) {
            abort(405);
        }

        // get menu
        $menu = GeneralHelper::getMenuByCode($accessCode);

        // if menu is null then 404
        abort_if(empty($menu) && $checkAuthorization, Response::HTTP_NOT_FOUND);

        // share with view
        if ($request->accepts(["text/html"])) {
            View::share('menu', $menu);
        }

        // check authorization
        if ($checkAuthorization) {
            // get permission
            $permissions = AccessService::getRolePermissionsByRoleId(Session::get('role_id'), $accessCode);

            // if permission null then 403
            abort_if(empty($permissions), Response::HTTP_FORBIDDEN);

            // share permission with view
            if ($request->accepts(["text/html"])) {
                View::share('permissions', $permissions);
            }
        } else {
            if ($request->accepts(["text/html"])) {
                View::share('menu', null);
                View::share('permissions', null);
            }
        }

        // render side menu if request accept text/html
        if ($renderSideMenu && $request->accepts(["text/html"])) {
            // get menuData dari cache if production
            if (app()->isProduction()) {
                $menuData = Cache::get(Session::get('role_id'));
            }

            // if menuData null
            if (empty($menuData)) {
                // delete cache
                Cache::forget(Session::get('role_id'));

                // get menu data
                $menuData = AccessService::getRoleAccessByRoleId(Session::get('role_id'));
                $menuData = AccessService::retiveAccessMenuByCodes($menuData);

                // jika tidak memiliki menu maka 403
                abort_if(empty($menuData), Response::HTTP_NOT_FOUND);

                // simpan menuData pada cache dengan expire time (2 jam)
                Cache::put(Session::get('role_id'), $menuData, (60 * 60 * 2));
            }

            // ambil html side menu
            $menuHtml = GeneralHelper::renderMenuHtml($menuData, $accessCode);

            /* Share dengan view */
            View::share('menuData', $menuData); // menu data
            View::share('menuHTML', $menuHtml); // menu html side bar
        }

        return $next($request);
    }
}
