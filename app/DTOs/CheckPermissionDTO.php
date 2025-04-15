<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckPermissionDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly string $permission_name,
    ) {}

    public static function fromRequest(Request $request, string $userId): self
    {
        Validator::make(
            ['user_id' => $userId],
            ['user_id' => 'required|uuid|exists:users,auth_user_id']
        )->validate();

        $validated = $request->validate([
            'permission_name' => 'required|string|exists:permissions,name'
        ]);

        return new self(
            user_id: $userId,
            permission_name: $validated['permission_name']
        );
    }
}
