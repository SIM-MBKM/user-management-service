<?php

namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles()
    {
        try {
            return $this->roleRepository->getAllRoles();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getRoleById($roleId)
    {
        try {
            return $this->roleRepository->getByIdWithPermissions($roleId);
        } catch (\Exception $e) {
            return null;
        }
    }
}
