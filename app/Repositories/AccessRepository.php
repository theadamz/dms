<?php

namespace App\Repositories;

use App\Models\Config\RoleAccess;
use App\Models\Config\UserAccess;
use App\Repositories\Contracts\AccessRepository as Contract;

class AccessRepository implements Contract
{

    public function isRoleHasAccess(string $roleId, string $code): bool
    {
        return RoleAccess::where([
            ['role_id', '=', $roleId],
            ['code', '=', $code],
        ])->exists();
    }

    public function isRoleAccessAllowed(string $roleId, string $code, string $permission): bool
    {
        // return false when data not found
        return RoleAccess::where([
            ['role_id', '=', $roleId],
            ['code', '=', $code],
            ['permission', '=', $permission],
            ['is_allowed', '=', true],
        ])->exists();
    }

    public function getRolePermissionsByRoleId(string $roleId, string $code): ?array
    {
        // variable
        $data = [];

        // get data access
        $accesses = RoleAccess::where([
            ['role_id', '=', $roleId],
            ['code', '=', $code],
        ])->get(['permission', 'is_allowed'])->toArray();

        foreach ($accesses as $access) {
            $data[$access['permission']] = boolval($access['is_allowed']);
        }

        return $data;
    }

    public function getRoleAccessByRoleId(string $roleId): array
    {
        // get data access
        return RoleAccess::where([
            ['role_id', '=', $roleId],
        ])->pluck('code')->unique()->toArray();
    }

    public function isUserHasAccess(string $userId, string $code): bool
    {
        return UserAccess::where([
            ['user_id', '=', $userId],
            ['code', '=', $code],
        ])->exists();
    }

    public function isUserAccessAllowed(string $userId, string $code, string $permission): bool
    {
        // return false when data not found
        return UserAccess::where([
            ['user_id', '=', $userId],
            ['code', '=', $code],
            ['permission', '=', $permission],
            ['is_allowed', '=', true],
        ])->exists();
    }
}
