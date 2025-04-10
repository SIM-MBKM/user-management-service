<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use SIMMBKM\ModService\Auth;
use SIMMBKM\ModService\Exception as ServiceException;

class CheckPermission
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(Request $request, Closure $next, string $groupPermission, string $permission, string $resourceTable): Response
    {
        //TODO: Temporary fix, change later
        $token = $request->bearerToken();
        if (is_null($token)) {
            return response()->json(['message' => 'Authorization token not found'], 401);
        }
        $authServiceResponse = AuthService::validateToken();

        // Check if the response has an error status
        if (isset($authServiceResponse->status) && $authServiceResponse->status === 'error') {
            ServiceException::on($authServiceResponse);
        }

        // Get the actual user data (either from response->data or response->user)
        $authUserData = isset($authServiceResponse->data) ? $authServiceResponse->data : $authServiceResponse;

        // If we have a user property, use that
        if (isset($authUserData->user)) {
            $authUserData = $authUserData->user;
        }

        // Log::debug('Auth User Data: ', (array)$authUserData);
        $user = $this->userService->findUserByAuthUserId($authUserData->data->user_id);
        // Log::debug('User Data: ', (array)$user);

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $fullPermission = "{$groupPermission}.{$permission}.{$resourceTable}";

        if (!$user->hasPermission($fullPermission)) {
            abort(403, "Required permission: {$fullPermission}");
        }

        return $next($request);
    }
}
