<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class PermissionDTO
{
    public function __construct(
        public readonly string $group_permission_id,
        public readonly string $name,
        public readonly string $description
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'group_permission_id' => 'required|uuid|exists:group_permissions,id',
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:65535'
        ]);

        return new self(
            group_permission_id: $validated['group_permission_id'],
            name: $validated['name'],
            description: $validated['description']
        );
    }
}
