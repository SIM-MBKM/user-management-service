<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $groupPermission, string $permission, string $resourceTable): Response
    {
        $user =  $request->user();

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
