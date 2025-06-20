<?php

namespace App\Services;

use App\DTOs\PaginationDTO;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllPermissions(array $filters = [], int $perPage = 10, int $page = 1): ?PaginationDTO
    {
        try {
            return $this->permissionRepository->getPaginatedPermissions($filters, $perPage, $page);
        } catch (\Exception $e) {
            Log::error('Error getting all permissions: ' . $e->getMessage());
            return null;
        }
    }

    public function getPermissionById(string $permissionId)
    {
        try {
            return $this->permissionRepository->getById($permissionId);
        } catch (\Exception $e) {
            Log::error('Error getting permission by ID: ' . $e->getMessage());
            return null;
        }
    }

    public function getPermissionIdsByNames(array $permissionNames)
    {
        try {
            return $this->permissionRepository->getIdsByNames($permissionNames);
        } catch (\Exception $e) {
            Log::error('Error converting permission names to IDs: ' . $e->getMessage());
            return [];
        }
    }

    public function getPermissionIdsByNamesDetailed(array $permissionNames)
    {
        try {
            return $this->permissionRepository->getIdsByNamesDetailed($permissionNames);
        } catch (\Exception $e) {
            Log::error('Error converting permission names to IDs: ' . $e->getMessage());
            return [];
        }
    }
}
