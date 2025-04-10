<?php

namespace App\Services;

use SIMMBKM\ModService\Service as BaseService;

class AuthService extends BaseService
{
    protected $baseUri = 'AUTH_SERVICE_URL';

    public static function getUserDataFromAuthService()
    {
        $response = self::get('/api/v1/auth/user');

        if (is_object($response) && property_exists($response, 'status')) {
            return $response;
        }

        return (object) [
            'status' => 'success',
            'data' => $response
        ];
    }

    public static function validateToken()
    {
        $response = self::post('/api/v1/auth/validate-token');

        if (is_object($response) && property_exists($response, 'status')) {
            return $response;
        }

        return (object) [
            'status' => 'success',
            'data' => $response
        ];
    }
}
