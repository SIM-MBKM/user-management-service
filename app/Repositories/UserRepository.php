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
        $user = User::find('auth_user_id', $authUserId);
        if (!$user) {
            throw new ModelNotFoundException("User not found: $authUserId");
        }
        return $user;
    }

    public function getAllUsers()
    {
        return User::with(['role', 'permissions'])->get();
    }

    public function updateUser(string $authUserId, array $data): User
    {
        $user = $this->getByAuthUserId($authUserId);
        $user->update($data);
        return $user->fresh();
    }
}
