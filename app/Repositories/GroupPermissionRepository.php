<?php

namespace App\Repositories;

use App\DTOs\GroupPermissionDetailDTO;
use App\DTOs\GroupPermissionDTO;
use App\DTOs\GroupPermissionListItemDTO;
use App\DTOs\PaginationDTO;
use App\Models\GroupPermission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GroupPermissionRepository
{
    protected $model;
    public function __construct(GroupPermission $model)
    {
        $this->model = $model;
    }

    public function getAllGroupPermissions(array $filters = []): Collection
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $groupPermissions = $query->with(['permissions' => function ($query) {
            $query->select('permissions.id', 'permissions.name', 'permissions.description', 'permissions.group_permission_id');
        }])
            ->select('id', 'name', 'description')
            ->get();

        // Map to DTOs
        return $groupPermissions->map(function ($groupPermission) {
            return GroupPermissionListItemDTO::fromModel($groupPermission);
        });
    }

    public function getPaginatedGroupPermissions(array $filters = [], int $perPage = 10, int $page): PaginationDTO
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $paginator = $query->with(['permissions' => function ($query) {
            $query->select('permissions.id', 'permissions.name', 'permissions.description', 'permissions.group_permission_id');
        }])
            ->select('id', 'name', 'description')
            ->paginate($perPage, ['*'], 'page', $page);

        // Transform the data in the paginator
        $transformedData = $paginator->getCollection()->map(function ($groupPermission) {
            return GroupPermissionListItemDTO::fromModel($groupPermission);
        })->all();

        return PaginationDTO::fromPaginator($paginator, $transformedData, request());
    }

    public function getByIdWithPermissions($groupPermissionId): ?GroupPermissionDetailDTO
    {
        $groupPermission = $this->model->with(['permissions' => function ($query) {
            $query->select('permissions.id', 'permissions.name', 'permissions.description', 'permissions.group_permission_id');
        }])
            ->select('id', 'name', 'description', 'created_at', 'updated_at')
            ->find($groupPermissionId);

        if (!$groupPermission) {
            return null;
        }

        return GroupPermissionDetailDTO::fromModel($groupPermission);
    }

    public function create(GroupPermissionDTO $dto): GroupPermissionDetailDTO
    {
        // Create the group permission
        $groupPermission = $this->model->create($dto->toArray());

        // Attach permissions if any
        if (!empty($dto->permission_ids)) {
            $groupPermission->permissions()->attach($dto->permission_ids);
        }

        // Fresh load with permissions
        $groupPermission = $groupPermission->fresh(['permissions']);

        return GroupPermissionDetailDTO::fromModel($groupPermission);
    }

    public function update($groupPermissionId, GroupPermissionDTO $dto): ?GroupPermissionDetailDTO
    {
        $groupPermission = $this->model->find($groupPermissionId);

        if (!$groupPermission) {
            return null;
        }

        // Update basic fields
        $groupPermission->update($dto->toArray());

        // Sync permissions if provided
        if (isset($dto->permission_ids)) {
            $groupPermission->permissions()->sync($dto->permission_ids);
        }

        // Fresh load with permissions
        $groupPermission = $groupPermission->fresh(['permissions']);

        return GroupPermissionDetailDTO::fromModel($groupPermission);
    }

    public function delete($groupPermissionId): bool
    {
        $groupPermission = $this->model->find($groupPermissionId);

        if (!$groupPermission) {
            return false;
        }

        // Detach permissions before deleting
        $groupPermission->permissions()->detach();

        return $groupPermission->delete();
    }

    protected function applyFilters($query, array $filters): void
    {
        // Filter by name (partial match)
        if (isset($filters['name'])) {
            $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }

        // Filter by description (partial match)
        if (isset($filters['description'])) {
            $query->where('description', 'LIKE', '%' . $filters['description'] . '%');
        }

        // Filter by created date range
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
    }
}
