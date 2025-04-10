<?php

namespace App\Services;

use App\Repositories\UserRepository;
use SIMMBKM\ModService\Exception as ServiceException;
use App\Services\AuthService;
use Illuminate\Support\Facades\Log;

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
            $authServiceResponse = AuthService::getUserDataFromAuthService();

            // Check if the response has an error status
            if (isset($authServiceResponse->status) && $authServiceResponse->status === 'error') {
                ServiceException::on($authServiceResponse);
            }

            // Get the actual user data (either from response->data or response->user)
            $authUserData = isset($authServiceResponse->data) ? $authServiceResponse->data : $authServiceResponse;

            // If we have a user property, use that
            if (isset($authUserData->user)) {
                $authUserData = $authUserData->user;
            }

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
}
