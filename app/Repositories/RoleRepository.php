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

    public function getAllRoles()
    {
        return Role::with('permissions')->get();
    }

    public function deleteRole(string $roleId): void
    {
        Role::destroy($roleId);
    }
}
