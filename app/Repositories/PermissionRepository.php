<?php

namespace App\Repositories;

use App\DTOs\PermissionDTO;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
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

    public function getByIds(array $permissionIds)
    {
        return Permission::whereIn('id', $permissionIds)->get();
    }

    public function getAllPermissions()
    {
        return Permission::with('group')->get();
    }

    public function deletePermission(string $permissionId): void
    {
        Permission::destroy($permissionId);
    }

    public function getIdsByNames(array $permissionNames)
    {
        return Permission::whereIn('name', $permissionNames)->pluck('id')->toArray();
    }

    public function getIdsByNamesDetailed(array $permissionNames)
    {
        return Permission::whereIn('name', $permissionNames)->get();
    }

    public function getByName(string $permissionName): ?Permission
    {
        return Permission::where('name', $permissionName)->first();
    }

    public function mergeDuplicatePermissions(?Collection $directPermissions, ?Collection $rolePermissions)
    {
        // Ensure collections exist and are not null
        $directPermissions = $directPermissions ?? collect([]);
        $rolePermissions = $rolePermissions ?? collect([]);

        // Merge and group permissions
        return $directPermissions->concat($rolePermissions)
            ->groupBy(function ($permission) {
                return optional($permission->groupPermission)->name ?? 'unknown';
            })
            ->map(function ($groupPermissions) {
                return $groupPermissions->pluck('name')->unique()->values()->toArray();
            });
    }
}
