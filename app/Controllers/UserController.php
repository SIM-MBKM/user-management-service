<?php

namespace App\Controllers;

use App\Libraries\Services\AuthService;
use App\Libraries\Services\UserService;
use App\Models\User;
use App\Services\UserService as ServicesUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SIMMBKM\ModService\Auth;
use SIMMBKM\ModService\Exception as ServiceException;

class UserController
{
    public function getUserById($userId)
    {
        $user = User::with([
            'role.permissions.group',
            'directPermissions.group'
        ])->where('auth_user_id', $userId)->firstOrFail();

        $allPermissions = $user->role->permissions
            ->merge($user->directPermissions)
            ->unique('id');

        $groupedPermissions = $allPermissions->groupBy(function ($permission) {
            return $permission->group->name;
        })->map(function ($group) {
            return $group->pluck('name')->toArray();
        });

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'age' => $user->age,
                'nrp' => $user->nrp,
                'role' => $user->role->name,
                'permissions' => $groupedPermissions
            ]
        ]);
    }

    public function testFunction(Request $request)
    {
        try {
            // Log the start of the function
            Log::info("Starting testFunction in UserController");

            // Make the service call
            $response = ServicesUserService::getUserData("9613e4c1-5807-4276-bbaf-dde98c510f52");

            // Check if response is null
            if ($response === null) {
                throw new \Exception("No response received from user service");
            }

            // Log the response for debugging
            Log::info("Received response from user service", ['response' => json_encode($response)]);

            // Handle error status
            if (isset($response->status) && $response->status === 'error') {
                $errorMessage = property_exists($response, 'message')
                    ? $response->message
                    : "Unknown error from user service";

                throw new \Exception($errorMessage);
            }

            // Check if data property exists and return it
            if (!property_exists($response, 'data')) {
                Log::warning("Response missing data property", ['response' => json_encode($response)]);
                return response()->json([
                    'success' => true,
                    'data' => $response // Return the whole response if no data property
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $response->data
            ]);
        } catch (\Exception $e) {
            // Log the error with context
            Log::error("Error in testFunction: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function testAuth(Request $request)
    // {
    //     // Step 1: Verify request availability
    //     dd([
    //         'Injected request' => $request,
    //         'Global request' => app('request'),
    //         'Is HTTP context' => !app()->runningInConsole()
    //     ]);

    //     // Step 2: Check after explicit binding
    //     app()->instance('request', $request);
    //     dd(Auth::check());
    // }
}
