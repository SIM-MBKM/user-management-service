<?php

namespace App\Controllers;

use App\DTOs\PermissionSearchDTO;
use App\DTOs\UserPermissionAssignDTO;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends BaseController
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
            return $this->successResponse($permissions);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getPermissionById($permissionId)
    {
        try {
            $permission = $this->permissionService->getPermissionById($permissionId);

            if (!$permission) {
                return $this->errorResponse('Permission not found', 404);
            }

            return $this->successResponse($permission);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
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
                return $this->errorResponse('No permissions found', 404);
            }

            return $this->successResponse($permissions);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
