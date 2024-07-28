<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Services\User\RestaurantService;

class RestaurantUserController extends BaseController
{
    protected $service;

    public function __construct(RestaurantService $service)
    {
        $this->service = $service;
    }
}
