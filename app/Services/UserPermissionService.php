<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;

class UserPermissionService
{
    protected $userRepository;
    protected $permissionRepository;

    public function __construct(
        UserRepository $userRepository,
        PermissionRepository $permissionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function getPermissionByUserId(string $userId)
    {
        try {
            $user = $this->userRepository->getByAuthUserId($userId);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found',
                    'data' => null
                ];
            }

            $userData = $user->only('id', 'auth_user_id', 'role_id', 'age', 'nrp', 'created_at', 'updated_at');

            // Get direct and role-based permissions
            $directPermissions = $user->directPermissions()->get();
            $rolePermissions = $user->role->permissions ?? collect([]);

            // Comprehensive permission mapping
            $permissionsMap = [
                'groups' => [], // Grouped by service/domain
                'sources' => [
                    'direct' => [], // Direct user permissions
                    'role' => []    // Role-based permissions
                ],
                'raw' => [
                    'direct' => [], // Raw direct permission details
                    'role' => []    // Raw role permission details
                ]
            ];

            // Process direct permissions
            $directPermissions->each(function ($permission) use (&$permissionsMap) {
                $groupName = $this->extractGroupName($permission->name);

                // Add to sources
                $permissionsMap['sources']['direct'][] = $permission->name;

                // Add to groups
                if (!isset($permissionsMap['groups'][$groupName])) {
                    $permissionsMap['groups'][$groupName] = [];
                }
                $permissionsMap['groups'][$groupName][] = $permission->name;

                // Add to raw
                $permissionsMap['raw']['direct'][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description ?? null,
                    'group_name' => optional($permission->groupPermission)->name ?? 'unknown'
                ];
            });

            // Process role permissions
            $rolePermissions->each(function ($permission) use (&$permissionsMap) {
                $groupName = $this->extractGroupName($permission->name);

                // Add to sources
                $permissionsMap['sources']['role'][] = $permission->name;

                // Add to groups
                if (!isset($permissionsMap['groups'][$groupName])) {
                    $permissionsMap['groups'][$groupName] = [];
                }
                $permissionsMap['groups'][$groupName][] = $permission->name;

                // Add to raw
                $permissionsMap['raw']['role'][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description ?? null,
                    'group_name' => optional($permission->groupPermission)->name ?? 'unknown'
                ];
            });

            return [
                'user' => $userData,
                'role' => $user->role->name ?? null,
                'summary' => [
                    'total_permissions' => count($directPermissions) + count($rolePermissions),
                    'direct_permissions_count' => $directPermissions->count(),
                    'role_permissions_count' => $rolePermissions->count(),
                    'unique_groups' => count($permissionsMap['groups'])
                ],
                'permissions' => $permissionsMap,
            ];
        } catch (\Exception $e) {
            // Log::error('Error getting user permissions: ' . $e->getMessage(), [
            //     'user_id' => $userId,
            //     'trace' => $e->getTraceAsString()
            // ]);
            return null;
        }
    }

    public function mergedPermissionByUserId(string $userId)
    {
        try {
            $user = $this->userRepository->getByAuthUserId($userId);

            if (!$user) {
                return null;
            }

            $userData = $user->only('id', 'auth_user_id', 'role_id', 'age', 'nrp', 'created_at', 'updated_at');

            // Get direct and role-based permissions
            $directPermissions = $user->directPermissions()->get();
            $rolePermissions = $user->role->permissions ?? collect([]);

            // Merge duplicate permissions
            return [
                'user' => $userData,
                'permissions' => $this->permissionRepository->mergeDuplicatePermissions($directPermissions, $rolePermissions)
            ];
        } catch (\Exception $e) {
            // Log::error('Error merging permissions: ' . $e->getMessage(), [
            //     'user_id' => $userId,
            //     'trace' => $e->getTraceAsString()
            // ]);
            return null;
        }
    }

    // Helper method to extract group name
    private function extractGroupName(string $permissionName): string
    {
        $parts = explode('.', $permissionName, 2);
        return count($parts) > 1 ? $parts[0] : 'unknown';
    }

    public function assignPermissionsToUser(string $userId, array $permissionIds)
    {
        try {
            $user = $this->userRepository->getByAuthUserId($userId);

            if (!$user) {
                return false;
            }

            $user->directPermissions()->syncWithoutDetaching($permissionIds);

            Log::info('Permissions assigned to user', [
                'user_id' => $userId,
                'permission_ids' => $permissionIds
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error assigning permissions to user: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    public function removePermissionFromUser(string $userId, string $permissionId)
    {
        try {
            $user = $this->userRepository->getByAuthUserId($userId);

            if (!$user) {
                return false;
            }

            // Use detach to remove a permission
            $user->directPermissions()->detach($permissionId);

            Log::info('Permission removed from user', [
                'user_id' => $userId,
                'permission_id' => $permissionId
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error removing permission from user: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    public function checkPermissionsByUserId(string $userId, string $permissionName)
    {
        try {
            $user = $this->userRepository->getByAuthUserId($userId);

            if (!$user) {
                return false;
            }

            return $user->hasPermission($permissionName);
        } catch (\Exception $e) {
            Log::error('Error checking permissions for user: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return false;
        }
    }
}
