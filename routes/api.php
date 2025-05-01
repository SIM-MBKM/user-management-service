<?php

use App\Controllers\UserController;
use App\Controllers\RoleController;
use App\Controllers\PermissionController;
use App\Controllers\GroupPermissionController;
use App\Controllers\UserPermissionController;
use App\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

/**
 * User Profile Endpoints
 * These endpoints manage user profile information
 */

// Get all users and specific user by ID (administrative)
Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,read-all,users'
])->group(function () {
    Route::get('/v1/user/service/users', [UserController::class, 'getAllUsers']);
    Route::get('/v1/user/service/by-user-id/{userId}', [UserController::class, 'getUserById'])
        ->where('userId', '[0-9a-fA-F\-]{36}');
});

// Current user profile operations (self)
Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,read,users'
])->group(function () {
    Route::get('/v1/user/service/users/me', [UserController::class, 'getUserMe']);
});

Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,update,users'
])->group(function () {
    Route::patch('/v1/user/service/users/update/me', [UserController::class, 'updateUserMe']);
});

// Admin user update operations (administrative)
Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,update-all,users'
])->group(function () {
    Route::patch('/v1/user/service/users/update/{userId}', [UserController::class, 'updateUserById'])
        ->where('userId', '[0-9a-fA-F\-]{36}');
});

/**
 * Role Management Endpoints
 * These endpoints handle role operations
 */
Route::middleware([
    //done but need some cleaning on response
    'auth',
    'check.permission:user_management_service,read-all,roles'
])->group(function () {
    Route::get('/v1/user/service/roles', [RoleController::class, 'getAllRoles']);
    Route::get('/v1/user/service/roles/{roleId}', [RoleController::class, 'getRoleById'])
        ->where('roleId', '[0-9a-fA-F\-]{36}');
});

/**
 * Permission Management Endpoints
 * These endpoints handle permission operations
 */
Route::middleware([
    //done but need some cleaning on response
    'auth',
    'check.permission:user_management_service,read-all,permissions'
])->group(function () {
    Route::get('/v1/user/service/permissions', [PermissionController::class, 'getAllPermissions']);
    Route::get('/v1/user/service/permissions/{permissionId}', [PermissionController::class, 'getPermissionById'])
        ->where('permissionId', '[0-9a-fA-F\-]{36}');
    Route::get('/v1/user/service/permissions/names', [PermissionController::class, 'getPermissionByNames']);
});

/**
 * Group Permission Endpoints
 * These endpoints handle permission group operations
 */
Route::middleware([
    //done but need some cleaning on response
    'auth',
    'check.permission:user_management_service,read-all,group_permissions'
])->group(function () {
    Route::get('/v1/user/service/group-permissions', [GroupPermissionController::class, 'getAllGroupPermissions']);
    Route::get('/v1/user/service/group-permissions/{groupId}', [GroupPermissionController::class, 'getGroupPermissionById'])
        ->where('groupId', '[0-9a-fA-F\-]{36}');
});

/**
 * User Permission Mapping Endpoints
 * These endpoints manage direct permissions assigned to users
 */
// Administrative operations (other users)
Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,read-all,user_permissions'
])->group(function () {
    Route::get('/v1/user/service/users/{userId}/permissions-detailed', [UserPermissionController::class, 'getPermissionsByUserId'])
        ->where('userId', '[0-9a-fA-F\-]{36}');
    Route::get('/v1/user/service/users/{userId}/permissions-simplified', [UserPermissionController::class, 'getPermissionsByUserIdSimplified'])
        ->where('userId', '[0-9a-fA-F\-]{36}');
});

Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,update-all,user_permissions'
])->group(function () {
    Route::post('/v1/user/service/users/{userId}/permissions', [UserPermissionController::class, 'assignPermissionsToUser'])
        ->where('userId', '[0-9a-fA-F\-]{36}');
});

Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,delete-all,user_permissions'
])->group(function () {
    Route::delete('/v1/user/service/users/{userId}/permissions/{permissionId}', [UserPermissionController::class, 'removePermissionFromUser'])
        ->where('userId', '[0-9a-fA-F\-]{36}')
        ->where('permissionId', '[0-9a-fA-F\-]{36}');
});

// Self operations (current user permissions)
Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,read,user_permissions'
])->group(function () {
    Route::get('/v1/user/service/users/me/permissions', [UserPermissionController::class, 'getMyPermissions']);

    // Check if user has specific permissions (for self)
    Route::post('/v1/user/service/users/me/check-permissions', [UserPermissionController::class, 'checkMyPermissions']);
});

// Check permissions for specific user (administrative)
Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,read-all,user_permissions'
])->group(function () {
    Route::post('/v1/user/service/users/{userId}/check-permissions', [UserPermissionController::class, 'checkUserPermissions'])
        ->where('userId', '[0-9a-fA-F\-]{36}');
});

/**
 * User Role Endpoints
 * These endpoints manage role assignments for users
 */
// Administrative operations (other users)
Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,read-all,roles'
])->group(function () {
    Route::get('/v1/user/service/users/{userId}/role', [UserRoleController::class, 'getUserRole'])
        ->where('userId', '[0-9a-fA-F\-]{36}');
});

Route::middleware([
    'auth',
    'check.permission:user_management_service,update-all,roles'
])->group(function () {
    Route::put('/v1/user/service/users/{userId}/role', [UserRoleController::class, 'assignRoleToUser'])
        ->where('userId', '[0-9a-fA-F\-]{36}');

    Route::patch('/v1/user/service/users/{userId}/role', [UserRoleController::class, 'removeRoleFromUser'])
        ->where('userId', '[0-9a-fA-F\-]{36}');
});

// Self operations (view own role)
Route::middleware([
    //done
    'auth',
    'check.permission:user_management_service,read,roles'
])->group(function () {
    Route::get('/v1/user/service/users/me/role', [UserRoleController::class, 'getMyRole']);
});
