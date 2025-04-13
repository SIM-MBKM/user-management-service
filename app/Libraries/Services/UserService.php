<?php

namespace App\Libraries\Services;

use Illuminate\Support\Facades\Http;
use SIMMBKM\ModService\Service as BaseService;

class UserService extends BaseService
{
    protected $baseUri = 'USER_MANAGEMENT_SERVICE_URL';
}
