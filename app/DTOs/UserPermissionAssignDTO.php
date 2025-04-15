<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserPermissionAssignDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly array $permission_ids,
    ) {}

    public static function fromRequest(Request $request, string $userId): self
    {
        Validator::make(
            ['user_id' => $userId],
            ['user_id' => 'required|uuid|exists:users,auth_user_id']
        )->validate();

        $validated = $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'string|exists:permissions,id'
        ]);

        return new self(
            user_id: $userId,
            permission_ids: $validated['permission_ids']
        );
    }

    public static function fromRequestWithNames(Request $request, string $userId): self
    {
        Validator::make(
            ['user_id' => $userId],
            ['user_id' => 'required|uuid|exists:users,auth_user_id']
        )->validate();

        $validated = $request->validate([
            'permission_names' => 'required|array',
            'permission_names.*' => 'string|exists:permissions,name'
        ]);

        // This needs permissionRepository to convert names to IDs
        // For now, we'll assume this is handled at the service level
        return new self(
            user_id: $userId,
            permission_ids: [] // This will be replaced with actual IDs at service level
        );
    }
}
