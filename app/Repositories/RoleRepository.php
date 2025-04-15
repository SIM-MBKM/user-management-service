<?php

namespace App\Repositories;

use App\DTOs\RoleDTO;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleRepository
{
    public function createRole(RoleDTO $dto)
    {
        return Role::create([
            'name' => $dto->name,
            'description' => $dto->description
        ]);
    }

    public function updateRole(string $roleId, array $data): Role
    {
        $role = $this->getById($roleId);
        $role->update($data);
        return $role->fresh();
    }

    public function getById(string $roleId): Role
    {
        $role = Role::find($roleId);
        if (!$role) {
            throw new ModelNotFoundException("Role not found with ID: $roleId");
        }
        return $role;
    }

    public function getByIdWithPermissions(string $roleId): array
    {
        $role = Role::with(['permissions' => function ($query) {
            $query->select('permissions.id', 'permissions.name', 'permissions.description');
        }])
            ->select('id', 'name', 'description')
            ->findOrFail($roleId);

        // Group permissions by service name
        $groupedPermissions = $role->permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'unknown';
        });

        // Format groups as key-value pairs where the key is the group name
        // and the value is an array of permission names
        $groups = [];
        foreach ($groupedPermissions as $groupName => $permissions) {
            $groups[$groupName] = $permissions->pluck('name')->toArray();
        }

        return [
            'id' => $role->id,
            'name' => $role->name,
            'description' => $role->description,
            'permissions' => $groups
        ];
    }

    public function getAllRoles()
    {
        $roles = Role::with(['permissions' => function ($query) {
            $query->select('permissions.name', 'permissions.description');
        }])
            ->select('id', 'name', 'description')
            ->get();

        $roles = $roles->map(function ($role) {
            $groupedPermissions = $role->permissions->groupBy(function ($permission) {
                $parts = explode('.', $permission->name);
                return $parts[0] ?? 'unknown';
            });

            $result = [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'permissions' => []
            ];

            // Convert to the desired format
            $groups = [];
            foreach ($groupedPermissions as $groupName => $permissions) {
                $permissionNames = $permissions->pluck('name')->toArray();
                $groups[$groupName] = $permissionNames;
            }

            $result['permissions'] = $groups;

            return $result;
        });

        return $roles;
    }

    public function deleteRole(string $roleId): void
    {
        Role::destroy($roleId);
    }
}
