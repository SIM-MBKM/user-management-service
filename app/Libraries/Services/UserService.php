<?php

namespace App\Libraries\Services;

use Illuminate\Support\Facades\Http;
use SimMbkm\ModService\Service as BaseService;

class UserService extends BaseService
{
    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.user_management_service.base_uri');
    }

    // public static function getUserData($authUserId)
    // {
    //     return self::get("api/v1/")
    // }
    // public function get($endpoint)
    // {
    //     // Override original package mod endpoints
    //     if (strpos($endpoint, 'api/v1/user/service/by-user-id/') === 0) {
    //         $userId = substr($endpoint, 31); // Length of 'api/v1/user/service/by-user-id/'

    //         $response = Http::timeout(15)
    //             ->get($baseUri . '/api/v1/user/service/by-user-id', [
    //                 'user_id' => $userId
    //             ]);

    //         return (object) [
    //             'success' => $response->successful(),
    //             'data' => $response->json()['user'] ?? null
    //         ];
    //     }

    //     throw new \Exception("Unknown user endpoint: {$endpoint}");
    // }
}
