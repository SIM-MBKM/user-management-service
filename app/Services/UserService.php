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
    protected $userPermissionService;

    public function __construct(
        UserRepository $userRepository,
        UserPermissionService $userPermissionService
    ) {
        $this->userRepository = $userRepository;
        $this->userPermissionService = $userPermissionService;
    }

    public function getUserById(string $authUserId)
    {
        try {
            $authUserData = Auth::info();
            $user = $this->userRepository->getUserWithPermissionsData($authUserId);
            $permissionData = $this->userPermissionService->mergedPermissionByUserId($authUserId);

            return [
                'auth_user_id' => $user->auth_user_id,
                'name' => $authUserData->name ?? null,
                'no_wa' => $authUserData->no_wa ?? null,
                'email' => $authUserData->email ?? null,
                'age' => $user->age,
                'nrp' => $user->nrp,
                'role' => $user->role->name,
                'permissions' => $permissionData['permissions'] ?? [],  // Only include the permissions, not the user data
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
