<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class UserRoleRepository
{
    public function getUserRole($userId)
    {
        $user =  User::select('id', 'auth_user_id', 'role_id')
            ->with(['role' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('auth_user_id', $userId)
            ->first();

        if (!$user) {
            throw new ModelNotFoundException("User not found: $userId");
        }

        return [
            'id' => $user->id,
            'auth_user_id' => $user->auth_user_id,
            'role' => $user->role->name ?? null,
        ];
    }

    public function assignRoleToUser($userId, $roleId)
    {
        $user = User::where('auth_user_id', $userId)->first();

        if (!$user) {
            throw new ModelNotFoundException("User not found: $userId");
        }

        $user->role_id = $roleId;
        $user->save();

        return $user;
    }

    public function removeRoleFromUser($userId, $roleId)
    {
        $user = User::where('auth_user_id', $userId)->first();
        // Log::info("Removing role $roleId from user $userId");
        if (!$user) {
            throw new ModelNotFoundException("User not found: $userId");
        }
        // Log::info("User found: $userId");
        if ($user->role_id !== $roleId) {
            throw new ModelNotFoundException("Role not assigned to user: $roleId");
        }
        // Log::info("Role $roleId found for user $userId");
        $user->update(['role_id' => Role::default()->first()->id]);
        // Log::info("Role $roleId removed from user $userId");
        return $user;
    }

    public function getRoleIdByName($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            throw new ModelNotFoundException("Role not found: $roleName");
        }

        return $role->id;
    }

    // public function changeToDefaultRole($userId)
    // {
    //     $user = User::where('auth_user_id', $userId)->first();

    //     if (!$user) {
    //         throw new ModelNotFoundException("User not found: $userId");
    //     }

    //     $defaultRole = Role::default()->first();
    //     if (!$defaultRole) {
    //         throw new ModelNotFoundException("Default role not found");
    //     }

    //     $user->role_id = $defaultRole->id;
    //     $user->save();

    //     return $user;
    // }
}
