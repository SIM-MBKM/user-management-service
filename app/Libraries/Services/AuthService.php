<?php

namespace App\Libraries\Services;

use Illuminate\Support\Facades\Http;
use SimMbkm\ModService\Service as BaseService;

class AuthService extends BaseService
{
    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.auth_service.base_uri');
    }
}
