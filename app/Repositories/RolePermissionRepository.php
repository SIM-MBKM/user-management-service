<?php

namespace App\Repositories;

use App\Models\Role;

class RolePermissionRepository
{
    public function getPermissionsByRole(Role $role)
    {
        return $role->permissions;
    }

    public function syncPermissions(Role $role, array $permissionIds): void
    {
        $role->permissions()->sync($permissionIds);
    }

    public function removePermission(Role $role, string $permissionId): void
    {
        $role->permissions()->detach($permissionId);
    }
}
