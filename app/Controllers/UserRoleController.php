<?php

namespace App\Controllers;

use App\DTOs\UserRoleAssignDTO;
use App\DTOs\UserRoleDTO;
use App\DTOs\UserRoleRemoveDTO;
use App\Services\UserRoleService;
use Illuminate\Http\Request;
use SIMMBKM\ModService\Auth;

class UserRoleController extends BaseController
{
    protected $userRoleService;

    public function __construct(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }

    public function getUserRole($userId)
    {
        try {
            $userRole = $this->userRoleService->getUserRole($userId);
            if (!$userRole) {
                return $this->errorResponse('User role not found', 404);
            }
            return $this->successResponse($userRole);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching user role', 500);
        }
    }

    public function assignRoleToUser(Request $request, $userId)
    {
        try {
            if ($request->has('role_id')) {
                $dto = UserRoleAssignDTO::fromRequest($request, $userId);

                $result = $this->userRoleService->assignRoleToUser(
                    $dto->user_id,
                    $dto->role_id
                );
            } else if ($request->has('role_name')) {
                $dto = UserRoleRemoveDTO::fromRequestWithNames($request, $userId);

                $roleId = $this->userRoleService->getRoleIdByName($request->input('role_name'));
                if (empty($roleId)) {
                    return $this->errorResponse('No valid role name provided', 400);
                }
                // Assuming the service method can handle role names
                $result = $this->userRoleService->assignRoleToUser(
                    $dto->user_id,
                    $roleId // This should be replaced with actual ID at service level
                );
            } else {
                return $this->errorResponse('Role ID or Role Name is required', 400);
            }

            if (!$result) {
                return $this->errorResponse('Failed to assign role to user', 400);
            }

            return $this->successResponse(null, 'Role assigned successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while assigning role to user: ' . $e->getMessage(), 500);
        }
    }

    public function removeRoleFromUser(Request $request, $userId)
    {
        try {
            if ($request->has('role_id')) {
                $dto = UserRoleRemoveDTO::fromRequest($request, $userId);

                $result = $this->userRoleService->removeRoleFromUser(
                    $dto->user_id,
                    $dto->role_id
                );
            } else if ($request->has('role_name')) {
                $dto = UserRoleRemoveDTO::fromRequestWithNames($request, $userId);

                $roleId = $this->userRoleService->getRoleIdByName($request->input('role_name'));
                if (empty($roleId)) {
                    return $this->errorResponse('No valid role name provided', 400);
                }
                // Assuming the service method can handle role names
                $result = $this->userRoleService->removeRoleFromUser(
                    $dto->user_id,
                    $roleId // This should be replaced with actual ID at service level
                );
            } else {
                return $this->errorResponse('Role ID or Role Name is required', 400);
            }

            if (!$result) {
                return $this->errorResponse('Failed to remove role from user', 400);
            }

            return $this->successResponse(null, 'Role removed successfully, and changed to default role');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while removing role from user: ' . $e->getMessage(), 500);
        }
    }

    public function getMyRole()
    {
        try {
            $userId = Auth::info()->user_id;
            $userRole = $this->userRoleService->getUserRole($userId);

            if (!$userRole) {
                return $this->errorResponse('User role not found', 404);
            }

            return $this->successResponse($userRole);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching user role: ' . $e->getMessage(), 500);
        }
    }

    public function changeToDosenPembimbing()
    {
        try {
            $userId = Auth::info()->user_id;
            $roleName = 'DOSEN PEMBIMBING';

            $roleId = $this->userRoleService->getRoleIdByName($roleName);
            if (empty($roleId)) {
                return $this->errorResponse('Role not found: ' . $roleName, 404);
            }

            $result = $this->userRoleService->assignRoleToUser($userId, $roleId);

            if (!$result) {
                return $this->errorResponse('Failed to change role to Dosen Pembimbing', 400);
            }

            return $this->successResponse(null, 'Role changed to Dosen Pembimbing successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while changing role to Dosen Pembimbing: ' . $e->getMessage(), 500);
        }
    }

    public function changeToDosenPemonev()
    {
        try {
            $userId = Auth::info()->user_id;
            $roleName = 'DOSEN PEMONEV';

            $roleId = $this->userRoleService->getRoleIdByName($roleName);
            if (empty($roleId)) {
                return $this->errorResponse('Role not found: ' . $roleName, 404);
            }

            $result = $this->userRoleService->assignRoleToUser($userId, $roleId);

            if (!$result) {
                return $this->errorResponse('Failed to change role to Dosen Pemonev', 400);
            }

            return $this->successResponse(null, 'Role changed to Dosen Pemonev successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while changing role to Dosen Pemonev: ' . $e->getMessage(), 500);
        }
    }
}
