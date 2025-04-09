<?php

namespace App\Repositories;

use App\Models\User;

class UserPermissionRepository
{
    public function getPermissionsByUser(User $user)
    {
        return $user->permissions;
    }

    public function syncPermissions(User $user, array $permissionIds): void
    {
        $user->permissions()->sync($permissionIds);
    }

    public function removePermission(User $user, string $permissionId): void
    {
        $user->permissions()->detach($permissionId);
    }
}
