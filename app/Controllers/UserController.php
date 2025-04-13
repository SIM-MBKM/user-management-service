<?php

namespace App\Controllers;

use App\DTOs\UserDTO;
use App\Models\User;
use App\Services\UserService as ServicesUserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SIMMBKM\ModService\Auth;
use UserService;

class UserController
{
    protected $userService;

    public function __construct(ServicesUserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUserById($userId)
    {
        try {
            $userData = $this->userService->getUserById($userId);

            if (!$userData) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $userData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllUsers()
    {
        try {
            $usersData = $this->userService->getAllUsers();

            return response()->json([
                'success' => true,
                'data' => $usersData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserMe()
    {
        try {
            $me = Auth::info()->user_id;
            $userData = $this->userService->getUserById($me);

            if (!$userData) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $userData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUserMe(Request $request)
    {
        try {
            $me = Auth::info()->user_id;
            $userData = $this->userService->findUserByAuthUserId($me);

            if (!$userData) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
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

            return response()->json([
                'success' => true,
                'data' => $updatedData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUserById($userId)
    {
        try {
            $userData = $this->userService->findUserByAuthUserId($userId);

            if (!$userData) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
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

            return response()->json([
                'success' => true,
                'data' => $updatedData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
