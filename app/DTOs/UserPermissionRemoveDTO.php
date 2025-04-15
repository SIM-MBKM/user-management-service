<?php

namespace App\DTOs;

use Illuminate\Support\Facades\Validator;

class UserPermissionRemoveDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly string $permission_id
    ) {}

    public static function fromRoute(string $userId, string $permissionId): self
    {
        Validator::make(
            ['user_id' => $userId],
            ['user_id' => 'required|uuid|exists:users,auth_user_id']
        )->validate();

        Validator::make(
            ['permission_id' => $permissionId],
            ['permission_id' => 'required|uuid|exists:permissions,id']
        )->validate();

        return new self(
            user_id: $userId,
            permission_id: $permissionId
        );
    }
}
