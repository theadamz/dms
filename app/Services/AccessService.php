<?php

namespace App\Services;

use App\Repositories\Contracts\AccessRepository;

class AccessService
{
    protected static AccessRepository $repository;

    public function __construct(AccessRepository $repository)
    {
        self::$repository = $repository;
    }

    public static function isRoleHasAccess(string $roleId, string $menuCode): bool
    {
        return self::$repository->isRoleHasAccess($roleId, $menuCode);
    }

    public static function isRoleAccessAllowed(string $roleId, string $accessCode, string $permission = 'read'): bool
    {
        return self::$repository->isRoleAccessAllowed($roleId, $accessCode, $permission);
    }

    public static function getRolePermissionsByRoleId(string $roleId, string $accessCode): ?array
    {
        return self::$repository->getRolePermissionsByRoleId($roleId, $accessCode);
    }

    public static function getRoleAccessByRoleId(string $roleId): array
    {
        return self::$repository->getRoleAccessByRoleId($roleId);
    }

    public static function retiveAccessMenuByCodes(array $menuCodes): array
    {
        // variabel
        $data = [];

        // ambil akses menu
        $accessMenus = collect(config('access.menus'));

        // looping $accessMenus
        foreach ($accessMenus as $accessMenu) {
            // jika code terdapat pada $menuCodes dan visible = true maka tambahkan
            if (in_array($accessMenu['code'], $menuCodes) && $accessMenu['visible'] === true) {
                // jika children tidak kosong
                if ($accessMenu['children'] !== null) {
                    $accessMenu['children'] = self::retiveAccessMenuChildrenByCodes($menuCodes, $accessMenu['children']);
                }

                $data[] = $accessMenu;
            }
        }

        return $data;
    }

    public static function retiveAccessMenuChildrenByCodes(array $menuCodes, ?array $childrenAccessMenus): array
    {
        // variabel
        $data = [];

        // looping $childrenAccessMenus
        foreach ($childrenAccessMenus as $accessMenu) {
            // jika code terdapat pada $menuCodes dan visible = true maka tambahkan
            if (in_array($accessMenu['code'], $menuCodes) && $accessMenu['visible'] === true) {
                // jika children tidak kosong
                if ($accessMenu['children'] !== null) {
                    $accessMenu['children'] = self::retiveAccessMenuChildrenByCodes($menuCodes, $accessMenu['children']);
                }

                $data[] = $accessMenu;
            }
        }

        return $data;
    }

    public static function isUserHasAccess(string $userId, string $menuCode): bool
    {
        return self::$repository->isUserHasAccess($userId, $menuCode);
    }

    public static function isUserAccessAllowed(string $userId, string $menuCode, string $permission = 'read'): bool
    {
        return self::$repository->isUserAccessAllowed($userId, $menuCode, $permission);
    }
}
