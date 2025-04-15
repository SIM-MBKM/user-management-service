<?php

namespace App\Controllers;

use App\DTOs\CheckPermissionDTO;
use App\DTOs\UserPermissionAssignDTO;
use App\DTOs\UserPermissionRemoveDTO;
use App\Services\PermissionService;
use App\Services\UserPermissionService;
use Exception;
use Illuminate\Http\Request;
use SIMMBKM\ModService\Auth;

class UserPermissionController
{
    protected $userPermissionService;
    protected $permissionService;

    public function __construct(
        UserPermissionService $userPermissionService,
        PermissionService $permissionService
    ) {
        $this->userPermissionService = $userPermissionService;
        $this->permissionService = $permissionService;
    }

    public function getPermissionsByUserId($userId)
    {
        try {
            $permissions = $this->userPermissionService->getPermissionByUserId($userId);

            if (!$permissions) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or no permissions assigned'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPermissionsByUserIdSimplified($userId)
    {
        // This method is similar to getPermissionsByUserId
        // but returns a simplified version of the permissions
        // (e.g., only the service names)

        try {
            $permissions = $this->userPermissionService->mergedPermissionByUserId($userId);

            if (!$permissions) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or no permissions assigned'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function assignPermissionsToUser(Request $request, $userId)
    {
        try {
            if ($request->has('permission_ids')) {
                $dto = UserPermissionAssignDTO::fromRequest($request, $userId);
                $result = $this->userPermissionService->assignPermissionsToUser(
                    $dto->user_id,
                    $dto->permission_ids
                );
            } else if ($request->has('permission_names')) {
                $dto = UserPermissionAssignDTO::fromRequestWithNames($request, $userId);

                $permissionIds = $this->permissionService->getPermissionIdsByNames($request->input('permission_names'));
                if (empty($permissionIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No valid permission names provided'
                    ], 400);
                }

                $result = $this->userPermissionService->assignPermissionsToUser(
                    $dto->user_id,
                    $permissionIds
                );
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission IDs or names are required'
                ], 400);
            }

            if (!$result) {
                throw new Exception("Failed to assign permissions");
            }

            return response()->json([
                'success' => true,
                'message' => 'Permissions assigned successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function removePermissionFromUser($userId, $permissionId)
    {
        try {
            $dto = UserPermissionRemoveDTO::fromRoute($userId, $permissionId);

            $result = $this->userPermissionService->removePermissionFromUser(
                $dto->user_id,
                $dto->permission_id
            );

            if (!$result) {
                throw new Exception("Failed to remove permission");
            }

            return response()->json([
                'success' => true,
                'message' => 'Permission removed successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getMyPermissions()
    {
        try {
            $authUserId = Auth::info()->user_id;

            $permissions = $this->userPermissionService->mergedPermissionByUserId($authUserId);
            if (!$permissions) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or no permissions assigned'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkMyPermissions(Request $request)
    {
        try {
            $authUserId = Auth::info()->user_id;
            $dto = CheckPermissionDTO::fromRequest($request, $authUserId);

            $permissions = $this->userPermissionService->checkPermissionsByUserId(
                $dto->user_id,
                $dto->permission_name
            );

            if (!$permissions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission not found or not assigned on related user'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkUserPermissions(Request $request, $userId)
    {
        try {
            $dto = CheckPermissionDTO::fromRequest($request, $userId);

            $permissions = $this->userPermissionService->checkPermissionsByUserId(
                $dto->user_id,
                $dto->permission_name
            );

            if (!$permissions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission not found or not assigned on related user'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
