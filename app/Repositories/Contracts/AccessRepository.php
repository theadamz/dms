<?php

namespace App\Repositories\Contracts;

interface AccessRepository
{
    public function isRoleHasAccess(string $roleId, string $accessCode): bool;
    public function isRoleAccessAllowed(string $roleId, string $accessCode, string $permission): bool;
    public function getRolePermissionsByRoleId(string $roleId, string $accessCode): ?array;
    public function getRoleAccessByRoleId(string $roleId): array;
    public function isUserHasAccess(string $userId, string $accessCode): bool;
    public function isUserAccessAllowed(string $userId, string $accessCode, string $permission): bool;
}
