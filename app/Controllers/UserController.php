<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\UserService as ServicesUserService;
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
}
