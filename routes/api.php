<?php

use App\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

//  User's profile related endpoints
// Route::get('/v1/user/service/test-auth', [UserController::class, 'testAuth']);
Route::middleware([
    'auth',
    'check.permission:user_management_service,read-all,users',
])->group(function () {
    Route::get('/v1/user/service/users', [UserController::class, 'getAllUsers']);
    Route::get('/v1/user/service/by-user-id/{userId}', [UserController::class, 'getUserById'])->where('userId', '[0-9a-fA-F\-]{36}');
});

Route::middleware([
    'check.permission:user_management_service,read,users'
])->group(function () {
    Route::get('/v1/user/service/users/me', [UserController::class, 'getUserMe']);
});

Route::middleware([
    'check.permission:user_management_service,update,users'
])->group(function () {
    Route::patch('/v1/user/service/users/update/me', [UserController::class, 'updateUserMe']);
});

Route::middleware([
    'check.permission:user_management_service,update-all,users'
])->group(function () {
    Route::patch('/v1/user/service/users/update/{userId}', [UserController::class, 'updateUserById'])->where('userId', '[0-9a-fA-F\-]{36}');
});

// Role related endpoints
// Route::middleware([
//     'check.permission:user_management_service,read-all,roles'
// ])->group(function)
Route::get('/v1/user/service/roles', [RoleController::class, 'getAllRoles']);
Route::get('/v1/user/service/roles/{roleId}', [RoleController::class, 'getRoleById']);
Route::post('/v1/user/service/roles/create', [RoleController::class, 'createRole']);
Route::patch('/v1/user/service/roles/update/{roleId}', [RoleController::class, 'updateRoleById']);
Route::delete('/v1/user/service/roles/delete/{roleId}', [RoleController::class, 'deleteRoleById']);

// Permission related endpoints
Route::get('/v1/user/service/permissions', [PermissionController::class, 'getAllPermissions']);
Route::get('/v1/user/service/permissions/permissionId', [PermissionController::class, 'getPermissionById']);
Route::post('/v1/user/service/permissions/create', [PermissionController::class, 'createPermission']);
Route::patch('/v1/user/service/permissions/update/{permissionId}', [PermissionController::class, 'updatePermissionById']);
Route::delete('/v1/user/service/permissions/delete/{permissionId}', [PermissionController::class, 'deletePermissionById']);

// Group Permission related endpoints
Route::get('/v1/user/service/group-permissions', [GroupPermissionController::class, 'getAllGroupPermissions']);
Route::get('/v1/user/service/group-permissions/{groupId}', [GroupPermissionController::class, 'getGroupPermissionById']);
Route::post('/v1/user/service/group-permissions/create', [GroupPermissionController::class, 'createGroupPermission']);
Route::patch('/v1/user/service/group-permissions/update/{groupId}', [GroupPermissionController::class, 'updateGroupPermissionById']);
Route::delete('/v1/user/service/group-permissions/delete/{groupId}', [GroupPermissionController::class, 'deleteGroupPermissionById']);

// Role Permission mapping endpoints
Route::get('/v1/user/service/roles/{roleId}/permissions', [RolePermissionController::class, 'getPermissionsByRoleId']);
Route::post('/v1/user/service/roles/{roleId}/permissions', [RolePermissionController::class, 'assignPermissionsToRole']);
Route::delete('/v1/user/service/roles/{roleId}/permissions/{permissionId}', [RolePermissionController::class, 'removePermissionFromRole']);

// User Permission mapping endpoints
Route::get('/v1/user/service/users/{userId}/permissions', [UserPermissionController::class, 'getPermissionsByUserId']);
Route::post('/v1/user/service/users/{userId}/permissions', [UserPermissionController::class, 'assignPermissionsToUser']);
Route::delete('/v1/user/service/users/{userId}/permissions/{permissionId}', [UserPermissionController::class, 'removePermissionFromUser']);

// User Role endpoints
Route::get('/v1/user/service/users/{userId}/role', [UserRoleController::class, 'getUserRole']);
Route::put('/v1/user/service/users/{userId}/role', [UserRoleController::class, 'assignRoleToUser']);

Route::get('/test', [UserController::class, 'testFunction']);
