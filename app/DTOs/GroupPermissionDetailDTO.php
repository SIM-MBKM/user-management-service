<?php

namespace App\DTOs;

class GroupPermissionDetailDTO
{
    public string $id;
    public string $name;
    public string $description;
    public string $created_at;
    public string $updated_at;
    public array $permissions;

    public static function fromModel($groupPermission): self
    {
        $dto = new self();
        $dto->id = $groupPermission->id;
        $dto->name = $groupPermission->name;
        $dto->description = $groupPermission->description;
        $dto->created_at = $groupPermission->created_at->format('Y-m-d H:i:s');
        $dto->updated_at = $groupPermission->updated_at->format('Y-m-d H:i:s');
        $dto->permissions = $groupPermission->permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description
            ];
        })->toArray();

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'permissions' => $this->permissions
        ];
    }
}
