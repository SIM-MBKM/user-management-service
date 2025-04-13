<?php

namespace App\Services;

use App\Repositories\UserRepository;
use SIMMBKM\ModService\Exception as ServiceException;
use App\Services\AuthService;
use Illuminate\Support\Facades\Log;
use SIMMBKM\ModService\Auth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById(string $authUserId)
    {
        try {
            $authUserData = Auth::info();

            $user = $this->userRepository->getUserWithPermissionsData($authUserId);

            $allPermissions = $user->role->permissions
                ->merge($user->directPermissions)
                ->unique('name');

            $groupedPermissions = $allPermissions->groupBy(function ($permission) {
                // Use accessor method to avoid undefined property errors
                return optional($permission->groupPermission)->name ?? 'unknown';
            })->map(function ($group) {
                return $group->pluck('name')->unique()->values()->toArray();
            });

            return [
                'auth_user_id' => $user->auth_user_id,
                'name' => $authUserData->name ?? null,
                'no_wa' => $authUserData->no_wa ?? null,
                'email' => $authUserData->email ?? null,
                'age' => $user->age,
                'nrp' => $user->nrp,
                'role' => $user->role->name,
                'permissions' => $groupedPermissions
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findUserByAuthUserId($authUserId)
    {
        try {
            return $this->userRepository->getByAuthUserId($authUserId);
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return null;
        }
    }

    public function getAllUsers()
    {
        try {
            return $this->userRepository->getAllUsers();
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return null;
        }
    }

    public function updateUserMe(string $authUserId, array $data)
    {
        try {
            return $this->userRepository->updateUser($authUserId, $data);
        } catch (\Exception $e) {
            return null;
        }
    }
}
