<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class GroupPermissionDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly ?array $permission_ids = []
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:65535'
        ]);

        return new self(
            name: $validated['name'],
            description: $validated['description']
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
