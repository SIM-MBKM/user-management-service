<?php

namespace App\Services;

use App\DTOs\PaginationDTO;
use App\Repositories\GroupPermissionRepository;

class GroupPermissionService
{
    protected $groupPermissionRepository;

    public function __construct(GroupPermissionRepository $groupPermissionRepository)
    {
        $this->groupPermissionRepository = $groupPermissionRepository;
    }

    public function getAllGroupPermissions(array $filters = [], int $perPage = 10, int $page = 1): ?PaginationDTO
    {
        try {
            return $this->groupPermissionRepository->getPaginatedGroupPermissions($filters, $perPage, $page);
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
