<?php

namespace App\DTOs;

class GroupPermissionListItemDTO
{
    public string $id;
    public string $name;
    public string $description;
    public array $permissions;

    public static function fromModel($groupPermission): self
    {
        $dto = new self();
        $dto->id = $groupPermission->id;
        $dto->name = $groupPermission->name;
        $dto->description = $groupPermission->description;
        $dto->permissions = $groupPermission->permissions->pluck('name')->toArray();

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'permissions' => $this->permissions
        ];
    }
}
