<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\Admin\DishService;
use Illuminate\Http\Request;

class DishHomeController extends BaseController
{
    //
    public function __construct(DishService $dishService)
    {
        $this->service = $dishService;
    }
}
