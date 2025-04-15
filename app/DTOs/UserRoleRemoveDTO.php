<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserRoleRemoveDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly string $role_id
    ) {}

    public static function fromRequest(Request $request, string $userId): self
    {
        Validator::make(
            ['user_id' => $userId],
            ['user_id' => 'required|uuid|exists:users,auth_user_id']
        )->validate();

        $validated = $request->validate([
            'role_id' => 'uuid|exists:roles,id'
        ]);

        return new self(
            user_id: $userId,
            role_id: $validated['role_id']
        );
    }

    public static function fromRequestWithNames(Request $request, string $userId): self
    {
        Validator::make(
            ['user_id' => $userId],
            ['user_id' => 'required|uuid|exists:users,auth_user_id']
        )->validate();

        $validated = $request->validate([
            'role_name' => 'required|string|exists:roles,name'
        ]);

        // This needs roleRepository to convert names to IDs
        // For now, we'll assume this is handled at the service level
        return new self(
            user_id: $userId,
            role_id: "" // This will be replaced with actual ID at service level
        );
    }
}
