<?php

namespace App\Libraries\Services;

use Illuminate\Support\Facades\Http;
use SIMMBKM\ModService\Service as BaseService;

class AuthService extends BaseService
{
    protected $baseUri = 'AUTH_SERVICE_URL';
}
