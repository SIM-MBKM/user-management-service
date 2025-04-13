<?php

namespace App\Repositories;

use App\DTOs\UserDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{
    public function updateOrCreateFromDto(UserDTO $dto): User
    {
        return User::updateOrCreate([
            'auth_user_id' => $dto->auth_user_id,
            'role_id' => $dto->role_id,
            'age' => $dto->age,
            'nrp' => $dto->nrp
        ]);
    }

    public function getByAuthUserId(string $authUserId): User
    {
        $user = User::where('auth_user_id', $authUserId)->first();
        if (!$user) {
            throw new ModelNotFoundException("User not found: $authUserId");
        }
        return $user;
    }

    public function getAllUsers($perPage = 10)
    {
        return User::with(['role:id,name,description'])
            ->select('id', 'auth_user_id', 'role_id', 'age', 'nrp', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function updateUser(string $authUserId, array $data): User
    {
        $user = $this->getByAuthUserId($authUserId);
        $user->update($data);
        return $user->fresh();
    }

    public function getUserWithPermissionsData(string $userId): User
    {
        return User::with([
            'role.permissions.groupPermission',
            'directPermissions.groupPermission'
        ])->where('auth_user_id', $userId)->first();
    }
}
