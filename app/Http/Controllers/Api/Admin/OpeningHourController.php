<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Admin\DishRequest;
use App\Http\Requests\Admin\OpeningHourRequest;
use App\Http\Services\Admin\DishService;
use App\Http\Services\Admin\OpeningHourService;
use Illuminate\Http\Request;

class OpeningHourController extends BaseController
{
    public function __construct(OpeningHourService $openingHourService)
    {
        $this->service = $openingHourService;
    }

    public function store(OpeningHourRequest $request)
    {
        return $this->service->store($request);
    }

    public function update(OpeningHourRequest $request, $id)
    {
        return $this->service->update($request, $id);
    }
}
