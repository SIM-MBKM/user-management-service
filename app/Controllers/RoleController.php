<?php

namespace App\Controllers;

use App\Services\RoleService;

class RoleController
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

            return response()->json([
                'success' => true,
                'data' => $roles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getRoleById($roleId)
    {
        try {
            $role = $this->roleService->getRoleById($roleId);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
