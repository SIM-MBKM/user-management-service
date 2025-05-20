<?php

namespace App\Repositories;

use App\DTOs\PaginationDTO;
use App\DTOs\UserDetailDTO;
use App\DTOs\UserDTO;
use App\DTOs\UserListItemDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getModelForPermissions(string $authUserId): User
    {
        $user = User::with([
            'role.permissions.groupPermission',
            'directPermissions.groupPermission'
        ])->where('auth_user_id', $authUserId)->first();

        if (!$user) {
            throw new ModelNotFoundException("User not found: $authUserId");
        }

        return $user;
    }

    public function updateOrCreateFromDto(UserDTO $dto): UserDetailDTO
    {
        $user = User::updateOrCreate([
            'auth_user_id' => $dto->auth_user_id,
            'role_id' => $dto->role_id,
            'email' => $dto->email,
            'nrp' => $dto->nrp
        ]);

        $user->load('role');

        return UserDetailDTO::fromModel($user);
    }

    public function getByAuthUserId(string $authUserId): UserDetailDTO
    {
        $user = User::with('role')->where('auth_user_id', $authUserId)->first();
        if (!$user) {
            throw new ModelNotFoundException("User not found: $authUserId");
        }

        return UserDetailDTO::fromModel($user);
    }

    public function getByEmail(string $email): UserDetailDTO
    {
        $user = User::with('role')->where('email', $email)->first();
        if (!$user) {
            throw new ModelNotFoundException("User not found: $email");
        }

        return UserDetailDTO::fromModel($user);
    }

    public function getAllUsers(array $filters = []): Collection
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $users = $query->select('id', 'auth_user_id', 'role_id', 'email', 'nrp', 'created_at')
            ->with(['role' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();

        return $users->map(function ($user) {
            return UserListItemDTO::fromModel($user);
        });
    }

    public function getPaginatedUsers(array $filters = [], int $perPage = 10, int $page = 1): PaginationDTO
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $paginator = $query->select('id', 'auth_user_id', 'role_id', 'email', 'nrp', 'created_at')
            ->with(['role' => function ($query) {
                $query->select('id', 'name');
            }])
            ->paginate($perPage, ['*'], 'page', $page);

        // Transform the data in the paginator
        $transformedData = $paginator->getCollection()->map(function ($user) {
            return UserListItemDTO::fromModel($user);
        })->all();
        // dd($transformedData);
        $dtoPaginator = PaginationDTO::fromPaginator($paginator, $transformedData, request());

        return $dtoPaginator;
    }

    public function updateUser(string $authUserId, array $data): UserDetailDTO
    {
        $user = User::where('auth_user_id', $authUserId)->first();
        if (!$user) {
            throw new ModelNotFoundException("User not found: $authUserId");
        }

        $user->update($data);
        $user->load('role');

        return UserDetailDTO::fromModel($user->fresh());
    }

    public function getUserWithPermissionsData(string $userId): UserDetailDTO
    {
        $user = User::with([
            'role.permissions.groupPermission',
            'directPermissions.groupPermission'
        ])->where('auth_user_id', $userId)->first();

        if (!$user) {
            throw new ModelNotFoundException("User not found: $userId");
        }

        return UserDetailDTO::fromModel($user);
    }

    protected function applyFilters($query, array $filters): void
    {
        // Filter by role_id
        if (isset($filters['role_name'])) {
            $query->whereHas('role', function ($q) use ($filters) {
                $q->where('name', 'LIKE', '%' . $filters['role_name'] . '%');
            });
        }

        // Filter by nrp (partial match)
        if (isset($filters['nrp'])) {
            $query->where('nrp', 'LIKE', '%' . $filters['nrp'] . '%');
        }

        // Filter by email
        if (isset($filters['email'])) {
            $query->where('email', 'LIKE', '%' . $filters['email'] . '%');
        }

        // REQUEST DIMAS
        // Filter by nrp (partial match)
        if (isset($filters['user_nrp'])) {
            $query->where('nrp', 'LIKE', '%' . $filters['user_nrp'] . '%');
        }

        // Filter by email
        if (isset($filters['user_email'])) {
            $query->where('email', 'LIKE', '%' . $filters['user_email'] . '%');
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
