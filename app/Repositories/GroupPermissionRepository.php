<?php

namespace App\Repositories;

use App\Models\GroupPermission;

class GroupPermissionRepository
{
    public function getAllGroupPermissions()
    {
        $groupPermissions = GroupPermission::with(['permissions' => function ($query) {
            $query->select('permissions.id', 'permissions.name', 'permissions.description', 'permissions.group_permission_id');
        }])
            ->select('id', 'name', 'description')
            ->get();

        return $groupPermissions->map(function ($group) {
            return [
                'id' => $group->id,
                'name' => $group->name,
                'description' => $group->description,
                'permissions' => $group->permissions->pluck('name')->toArray()
            ];
        });
    }

    public function getByIdWithPermissions($groupPermissionId)
    {
        $group = GroupPermission::with(['permissions' => function ($query) {
            $query->select('permissions.id', 'permissions.name', 'permissions.description', 'permissions.group_permission_id');
        }])
            ->select('id', 'name', 'description')
            ->findOrFail($groupPermissionId);

        return [
            'id' => $group->id,
            'name' => $group->name,
            'description' => $group->description,
            'permissions' => $group->permissions->pluck('name')->toArray()
        ];
    }
}
