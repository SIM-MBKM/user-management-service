<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class UserPermissionDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly string $permission_id,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'permission_id' => 'required|uuid|exists:permissions,id'
        ]);

        return new self(
            user_id: $validated['user_id'],
            permission_id: $validated['permission_id']
        );
    }
}
