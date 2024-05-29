<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Services\Admin\DishService;
use Illuminate\Http\Request;

class DishController extends BaseController
{
    public function __construct(DishService $dishService)
    {
        $this->service = $dishService;
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($request, $id);
    }
}
