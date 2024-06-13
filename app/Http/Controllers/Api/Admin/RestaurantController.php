<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
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
        return $this->service->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($request, $id);
    }

    public function getAllCategoriesKeyValue(){
        return $this->service->getAllCategoriesKeyValue();
    }
}
