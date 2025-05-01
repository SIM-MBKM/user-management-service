<?php

namespace App\Repositories;

use App\DTOs\PaginationDTO;
use App\DTOs\PermissionDetailDTO;
use App\DTOs\PermissionDTO;
use App\DTOs\PermissionListItemDTO;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionRepository
{
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
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

        // Filter by group name
        if (isset($filters['group_name'])) {
            $query->whereHas('groupPermission', function ($q) use ($filters) {
                $q->where('name', 'LIKE', '%' . $filters['group_name'] . '%');
            });
        }

        // Filter by group_permission_id
        if (isset($filters['group_permission_id'])) {
            $query->where('group_permission_id', $filters['group_permission_id']);
        }

        // Filter by created date range
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
    }

    public function createFromDto(PermissionDTO $dto): PermissionDetailDTO
    {
        $permission = Permission::create([
            'group_permission_id' => $dto->group_permission_id,
            'name' => $dto->name,
            'description' => $dto->description
        ]);

        $permission->load('groupPermission');

        return PermissionDetailDTO::fromModel($permission);
    }

    public function updateFromDto(string $id, PermissionDTO $dto): PermissionDetailDTO
    {
        $permission = Permission::find($id);

        if (!$permission) {
            throw new ModelNotFoundException("Permission not found: $id");
        }

        $permission->update([
            'group_permission_id' => $dto->group_permission_id,
            'name' => $dto->name,
            'description' => $dto->description
        ]);

        $permission->load('groupPermission');

        return PermissionDetailDTO::fromModel($permission->fresh());
    }

    public function getById(string $permissionId): PermissionDetailDTO
    {
        $permission = Permission::find($permissionId);
        if (!$permission) {
            throw new ModelNotFoundException("Permission not found with ID: $permissionId");
        }
        return PermissionDetailDTO::fromModel($permission);
    }

    public function getByIds(array $permissionIds)
    {
        return Permission::whereIn('id', $permissionIds)->get();
    }

    public function getAllPermissions(array $filters = []): Collection
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $permissions = $query->select('id', 'group_permission_id', 'name', 'description', 'created_at')
            ->with(['groupPermission' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();

        return $permissions->map(function ($permission) {
            return PermissionListItemDTO::fromModel($permission);
        });
    }

    public function getPaginatedPermissions(array $filters = [], int $perPage = 10, int $page = 1): PaginationDTO
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $paginator = $query->select('id', 'group_permission_id', 'name', 'description', 'created_at')
            ->with(['groupPermission' => function ($query) {
                $query->select('id', 'name');
            }])
            ->paginate($perPage, ['*'], 'page', $page);

        // Transform the data in the paginator
        $transformedData = $paginator->getCollection()->map(function ($permission) {
            return PermissionListItemDTO::fromModel($permission);
        })->all();

        $dtoPaginator = PaginationDTO::fromPaginator($paginator, $transformedData, request());

        return $dtoPaginator;
    }

    public function deletePermission(string $permissionId): void
    {
        Permission::destroy($permissionId);
    }

    public function getIdsByNames(array $permissionNames)
    {
        return Permission::whereIn('name', $permissionNames)->pluck('id')->toArray();
    }

    public function getIdsByNamesDetailed(array $permissionNames)
    {
        return Permission::whereIn('name', $permissionNames)->get();
    }

    public function getByName(string $permissionName): ?Permission
    {
        return Permission::where('name', $permissionName)->first();
    }

    public function mergeDuplicatePermissions(?Collection $directPermissions, ?Collection $rolePermissions)
    {
        // Ensure collections exist and are not null
        $directPermissions = $directPermissions ?? collect([]);
        $rolePermissions = $rolePermissions ?? collect([]);

        // Merge and group permissions
        return $directPermissions->concat($rolePermissions)
            ->groupBy(function ($permission) {
                return optional($permission->groupPermission)->name ?? 'unknown';
            })
            ->map(function ($groupPermissions) {
                return $groupPermissions->pluck('name')->unique()->values()->toArray();
            });
    }
}
