<?php

namespace App\Controllers;

use App\Services\RoleService;

class RoleController extends BaseController
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function getAllRoles()
    {
        try {
            $roles = $this->roleService->getAllRoles();
            return $this->successResponse($roles);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getRoleById($roleId)
    {
        try {
            $role = $this->roleService->getRoleById($roleId);

            if (!$role) {
                return $this->errorResponse('Role not found', 404);
            }

            return $this->successResponse($role);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
