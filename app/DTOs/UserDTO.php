<?php

namespace App\DTOs;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UserDTO
{
    public function __construct(
        public readonly string $auth_user_id,
        public readonly string $role_id,
        public readonly ?int $age,
        public readonly ?string $nrp
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'auth_user_id' => 'required|uuid',
            'role_id' => 'sometimes|uuid|exists:roles,id',
            'age' => 'nullable|integer|min:0|max:120',
            'nrp' => 'nullable|string|max:20',
        ]);

        return new self(
            auth_user_id: $validated['auth_user_id'],
            role_id: $validated['role_id'] ?? Role::default()->first()->id,
            age: $validated['age'] ?? null,
            nrp: $validated['nrp'] ?? null,
        );
    }

    public static function fromQueueMessage(array $data): self
    {
        $validated = Validator::make($data, [
            'auth_user_id' => 'required|uuid',
            'role_id' => 'sometimes|uuid|exists:roles,id',
            'age' => 'nullable|integer|min:0|max:120',
            'nrp' => 'nullable|string|max:20',
        ])->validate();

        return new self(
            auth_user_id: $validated['auth_user_id'],
            role_id: $validated['role_id'] ?? Role::default()->first()->id,
            age: $validated['age'] ?? null,
            nrp: $validated['nrp'] ?? null,
        );
    }
}
