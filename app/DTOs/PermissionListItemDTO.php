<?php

namespace App\DTOs;

use App\Models\Permission;

class PermissionListItemDTO
{
    public readonly string $id;
    public readonly string $name;
    public readonly ?string $description;
    public readonly ?string $group_name;
    public readonly ?string $created_at;

    public function __construct(
        string $id,
        string $name,
        ?string $description = null,
        ?string $group_name = null,
        ?string $created_at = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->group_name = $group_name;
        $this->created_at = $created_at;
    }

    public static function fromModel(Permission $permission): self
    {
        return new self(
            $permission->id,
            $permission->name,
            $permission->description,
            $permission->groupPermission->name ?? null,
            $permission->created_at?->format('Y-m-d H:i:s')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'group_name' => $this->group_name,
            'created_at' => $this->created_at,
        ];
    }
}
