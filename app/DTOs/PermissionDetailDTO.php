<?php

namespace App\DTOs;

use App\Models\Permission;

class PermissionDetailDTO
{
    public readonly string $id;
    public readonly string $group_permission_id;
    public readonly string $name;
    public readonly ?string $description;
    public readonly ?string $group_name;
    public readonly ?string $created_at;
    public readonly ?string $updated_at;

    public function __construct(
        string $id,
        string $group_permission_id,
        string $name,
        ?string $description = null,
        ?string $group_name = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->group_permission_id = $group_permission_id;
        $this->name = $name;
        $this->description = $description;
        $this->group_name = $group_name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public static function fromModel(Permission $permission): self
    {
        return new self(
            $permission->id,
            $permission->group_permission_id,
            $permission->name,
            $permission->description,
            $permission->groupPermission->name ?? null,
            $permission->created_at?->format('Y-m-d H:i:s'),
            $permission->updated_at?->format('Y-m-d H:i:s')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'group_permission_id' => $this->group_permission_id,
            'name' => $this->name,
            'description' => $this->description,
            'group_name' => $this->group_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
