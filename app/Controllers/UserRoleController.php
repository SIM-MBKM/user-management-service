<?php

namespace App\Controllers;

use App\DTOs\UserRoleAssignDTO;
use App\DTOs\UserRoleDTO;
use App\DTOs\UserRoleRemoveDTO;
use App\Services\UserRoleService;
use Illuminate\Http\Request;
use SIMMBKM\ModService\Auth;

class UserRoleController
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
                return response()->json([
                    'success' => false,
                    'message' => 'User role not found'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $userRole
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching user role'
            ], 500);
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
                    return response()->json([
                        'success' => false,
                        'message' => 'No valid role name provided'
                    ], 400);
                }
                // Assuming the service method can handle role names
                $result = $this->userRoleService->assignRoleToUser(
                    $dto->user_id,
                    $roleId // This should be replaced with actual ID at service level
                );
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Role ID or Role Name is required'
                ], 400);
            }


            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to assign role to user'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role assigned successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while assigning role to user: ' . $e->getMessage()
            ], 500);
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
                    return response()->json([
                        'success' => false,
                        'message' => 'No valid role name provided'
                    ], 400);
                }
                // Assuming the service method can handle role names
                $result = $this->userRoleService->removeRoleFromUser(
                    $dto->user_id,
                    $roleId // This should be replaced with actual ID at service level
                );
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Role ID or Role Name is required'
                ], 400);
            }

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to remove role from user'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role removed successfully, and changed to default role',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing role from user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMyRole()
    {
        try {
            $userId = Auth::info()->user_id;
            $userRole = $this->userRoleService->getUserRole($userId);

            if (!$userRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'User role not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $userRole
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching user role: ' . $e->getMessage()
            ], 500);
        }
    }
}
