<?php

namespace App\DTOs;

class UserDetailDTO
{
    public string $id;
    public string $auth_user_id;
    public string $role_id;
    public string $email;
    public ?string $nrp;
    public string $created_at;
    public string $updated_at;
    public string $role_name;
    public array $permissions = [];

    public static function fromModel($user): self
    {
        $dto = new self();
        $dto->id = $user->id;
        $dto->auth_user_id = $user->auth_user_id;
        $dto->role_id = $user->role_id;
        $dto->email = $user->email;
        $dto->nrp = $user->nrp;
        $dto->created_at = $user->created_at->format('Y-m-d H:i:s');
        $dto->updated_at = $user->updated_at->format('Y-m-d H:i:s');
        $dto->role_name = $user->role->name;

        // If permissions were loaded, add them to the DTO
        if ($user->relationLoaded('role') && $user->role->relationLoaded('permissions')) {
            // Get role permissions
            $rolePermissions = $user->role->permissions->pluck('name')->toArray();

            // Get direct permissions if they were loaded
            $directPermissions = [];
            if ($user->relationLoaded('directPermissions')) {
                $directPermissions = $user->directPermissions->pluck('name')->toArray();
            }

            // Merge and deduplicate permissions
            $dto->permissions = array_values(array_unique(array_merge($rolePermissions, $directPermissions)));
        }

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'auth_user_id' => $this->auth_user_id,
            'role_id' => $this->role_id,
            'email' => $this->email,
            'nrp' => $this->nrp,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role' => $this->role_name,
            'permissions' => $this->permissions
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role_name === 'ADMIN';
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions);
    }
}
