<?php

namespace App\Controllers;

use App\DTOs\PermissionSearchDTO;
use App\DTOs\UserPermissionAssignDTO;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function getAllPermissions()
    {
        try {
            $permissions = $this->permissionService->getAllPermissions();

            return response()->json([
                'success' => true,
                'data' => $permissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPermissionById($permissionId)
    {
        try {
            $permission = $this->permissionService->getPermissionById($permissionId);

            if (!$permission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $permission
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPermissionByNames(Request $request)
    {
        try {
            // Create DTO and validate request
            $dto = PermissionSearchDTO::fromRequestWithNamesForGetPermissionIds($request);

            // Get permission IDs
            $permissions = $this->permissionService->getPermissionIdsByNamesDetailed($dto->permissionNames);

            // Check if any permissions were found
            if (!$permissions) {
                return response()->json([
                    'success' => false,
                    'message' => 'No permissions found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $permissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
