<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Admin\RestaurantRequest;
use App\Http\Services\Admin\RestaurantService;
use Illuminate\Http\Request;

class RestaurantController extends BaseController
{
    public function __construct(RestaurantService $restaurantService)
    {
        $this->service = $restaurantService;
    }

    public function store(Request $request)
    {
        return $request -> all();
    }

    public function update(RestaurantRequest $request, $id)
    {
        return $this->service->update($request, $id);
    }

    public function getAllRestaurantKeyValue(){
        return $this->service->getAllRestaurantKeyValue();
    }
}
