<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserRoleRepository;
use Illuminate\Support\Facades\Log;

class UserRoleService
{
    protected $userRoleRepository;
    protected $userRepository;

    public function __construct(UserRoleRepository $userRoleRepository, UserRepository $userRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
        $this->userRepository = $userRepository;
    }

    public function getUserRole($userId)
    {
        try {
            return $this->userRoleRepository->getUserRole($userId);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function assignRoleToUser($userId, $roleId)
    {
        try {
            return $this->userRoleRepository->assignRoleToUser($userId, $roleId);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function removeRoleFromUser($userId, $roleId)
    {
        try {
            $query = $this->userRoleRepository->removeRoleFromUser($userId, $roleId);
            if (!$query) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getRoleIdByName($roleName)
    {
        try {
            return $this->userRoleRepository->getRoleIdByName($roleName);
        } catch (\Exception $e) {
            return null;
        }
    }

    // public function changeToDefaultRole($userId)
    // {
    //     try {
    //         return $this->userRoleRepository->changeToDefaultRole($userId);
    //     } catch (\Exception $e) {
    //         return false;
    //     }
    // }
}
