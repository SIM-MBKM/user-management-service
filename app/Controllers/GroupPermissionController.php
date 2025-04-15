<?php

namespace App\Controllers;

use App\Services\GroupPermissionService;

class GroupPermissionController
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

            return response()->json([
                'success' => true,
                'data' => $groupPermissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getGroupPermissionById($groupPermissionId)
    {
        try {
            $groupPermission = $this->groupPermissionService->getGroupPermissionById($groupPermissionId);

            if (!$groupPermission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Group Permission not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $groupPermission
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
