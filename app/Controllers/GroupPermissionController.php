<?php

namespace App\Controllers;

use App\Services\GroupPermissionService;
use Illuminate\Http\Request;

class GroupPermissionController extends BaseController
{
    protected $groupPermissionService;

    public function __construct(GroupPermissionService $groupPermissionService)
    {
        $this->groupPermissionService = $groupPermissionService;
    }

    public function getAllGroupPermissions(Request $request)
    {
        try {
            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 10);
            $filters = $request->only([
                'name',
                'description',
                'date_from',
                'date_to'
            ]);

            $groupPermissions = $this->groupPermissionService->getAllGroupPermissions($filters, $perPage, $page);
            if ($groupPermissions->isEmpty()) {
                return $this->errorResponse('No group permissions found', 404);
            }

            return $this->successResponse($groupPermissions, 'Group Permissions retrieved successfully');
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

            return $this->successResponse($groupPermission, 'Group Permission retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
