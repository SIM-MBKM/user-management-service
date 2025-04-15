<?php

namespace App\Services;

use App\Repositories\GroupPermissionRepository;

class GroupPermissionService
{
    protected $groupPermissionRepository;

    public function __construct(GroupPermissionRepository $groupPermissionRepository)
    {
        $this->groupPermissionRepository = $groupPermissionRepository;
    }

    public function getAllGroupPermissions()
    {
        try {
            return $this->groupPermissionRepository->getAllGroupPermissions();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getGroupPermissionById($groupPermissionId)
    {
        try {
            return $this->groupPermissionRepository->getByIdWithPermissions($groupPermissionId);
        } catch (\Exception $e) {
            return null;
        }
    }
}
