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

    public function getAllUsers()
    {
        return User::select('id', 'auth_user_id', 'role_id', 'age', 'nrp', 'created_at', 'updated_at')
            ->with(['role' => function ($query) {
                $query->select('id', 'name');
            }])
            ->paginate(10)
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'auth_user_id' => $user->auth_user_id,
                    'age' => $user->age,
                    'nrp' => $user->nrp,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'role' => $user->role->name ?? null,
                ];
            });
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
