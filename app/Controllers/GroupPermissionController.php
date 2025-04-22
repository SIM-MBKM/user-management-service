<?php

namespace App\Controllers;

use App\Services\GroupPermissionService;

class GroupPermissionController extends BaseController
{
    protected $groupPermissionService;

    public function __construct(GroupPermissionService $groupPermissionService)
    {
        $this->groupPermissionService = $groupPermissionService;
    }

    public function getAllGroupPermissions()
    {
        try {
            $groupPermissions = $this->groupPermissionService->getAllGroupPermissions();
            return $this->successResponse($groupPermissions);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getGroupPermissionById($groupPermissionId)
    {
        try {
            $groupPermission = $this->groupPermissionService->getGroupPermissionById($groupPermissionId);

            if (!$groupPermission) {
                return $this->errorResponse('Group Permission not found', 404);
            }

            return $this->successResponse($groupPermission);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
