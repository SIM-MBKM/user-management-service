<?php

namespace App\Repositories;

use App\DTOs\PermissionDTO;
use App\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionRepository
{
    public function createPermission(PermissionDTO $dto): Permission
    {
        return Permission::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'group_id' => $dto->group_permission_id
        ]);
    }

    public function updatePermission(string $permissionId, array $data): Permission
    {
        $permission = $this->getById($permissionId);
        $permission->update($data);
        return $permission->fresh();
    }

    public function getById(string $permissionId): Permission
    {
        $permission = Permission::find($permissionId);
        if (!$permission) {
            throw new ModelNotFoundException("Permission not found with ID: $permissionId");
        }
        return $permission;
    }

    public function getAllPermissions()
    {
        return Permission::with('group')->get();
    }

    public function deletePermission(string $permissionId): void
    {
        Permission::destroy($permissionId);
    }
}
