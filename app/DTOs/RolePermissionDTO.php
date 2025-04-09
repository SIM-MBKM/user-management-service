<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class RolePermissionDTO
{
    public function __construct(
        public readonly string $role_id,
        public readonly string $permission_id,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'role_id' => 'required|uuid|exists:roles,id',
            'permission_id' => 'required|uuid|exists:permissions,id'
        ]);

        return new self(
            role_id: $validated['role_id'],
            permission_id: $validated['permission_id']
        );
    }
}
