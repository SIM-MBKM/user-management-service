<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class PermissionSearchDTO
{
    /**
     * Create a PermissionSearchDTO instance.
     *
     * @param array $permissionNames
     */
    public function __construct(
        public readonly array $permissionNames
    ) {}

    /**
     * Create DTO from request with permission names.
     *
     * @param Request $request
     * @return self
     */
    public static function fromRequestWithNamesForGetPermissionIds(Request $request): self
    {
        $validated = $request->validate([
            'permission_names' => 'required|array',
            'permission_names.*' => 'string|exists:permissions,name'
        ]);

        return new self(
            permissionNames: $validated['permission_names']
        );
    }
}
