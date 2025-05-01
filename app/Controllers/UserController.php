<?php

namespace App\Controllers;

use App\DTOs\UserDTO;
use App\Models\Role;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService as ServicesUserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SIMMBKM\ModService\Auth;
use UserService;

class UserController extends BaseController
{
    protected $userService;
    protected $roleService;

    public function __construct(ServicesUserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function getUserById($userId)
    {
        try {
            $userData = $this->userService->getUserById($userId);

            if (!$userData) {
                return $this->errorResponse('User not found', 404);
            }

            return $this->successResponse($userData);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getAllUsers(Request $request)
    {
        try {
            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 10);
            $filters = request()->only([
                'role_name',
                'nrp',
                'min_age',
                'max_age',
                'date_from',
                'date_to',
            ]);

            $usersData = $this->userService->getPaginatedUsers($filters, $perPage, $page);
            if ($usersData->isEmpty()) {
                return $this->errorResponse('No users found', 404);
            }

            return $this->successResponse($usersData->toArray(), 'Users retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getUserMe()
    {
        try {
            $me = Auth::info()->user_id;
            $userData = $this->userService->getUserById($me);

            if (!$userData) {
                return $this->errorResponse('User not found', 404);
            }

            return $this->successResponse($userData);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function updateUserMe(Request $request)
    {
        try {
            $me = Auth::info()->user_id;
            $userData = $this->userService->findUserByAuthUserId($me);

            if (!$userData) {
                return $this->errorResponse('User not found', 404);
            }

            $mergedData = array_merge([
                'auth_user_id' => $me,
                'role_id' => $userData->role_id,
                'age' => $userData->age,
                'nrp' => $userData->nrp,
            ], request()->all());

            $dtoRequest = new Request($mergedData);
            $dto = UserDTO::fromRequest($dtoRequest);

            $updateData = [
                'age' => $dto->age,
                'nrp' => $dto->nrp
            ];

            $query = $this->userService->updateUserMe($me, $updateData);

            if (!$query) {
                throw new Exception("Failed to update data");
            }

            $updatedData = $this->userService->getUserById($me);

            return $this->successResponse($updatedData);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function updateUserById($userId)
    {
        try {
            $userData = $this->userService->findUserByAuthUserId($userId);

            if (!$userData) {
                return $this->errorResponse('User not found', 404);
            }

            $mergedData = array_merge([
                'auth_user_id' => $userId,
                'role_id' => $userData->role_id,
                'age' => $userData->age,
                'nrp' => $userData->nrp,
            ], request()->all());

            $dtoRequest = new Request($mergedData);
            $dto = UserDTO::fromRequest($dtoRequest);

            $updateData = [
                'age' => $dto->age,
                'nrp' => $dto->nrp
            ];

            $query = $this->userService->updateUserMe($userId, $updateData);

            if (!$query) {
                throw new Exception("Failed to update data");
            }

            $updatedData = $this->userService->getUserById($userId);

            return $this->successResponse($updatedData);
        } catch (\Exception $e) {
            // Note: Fixed missing status code in the original controller
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
