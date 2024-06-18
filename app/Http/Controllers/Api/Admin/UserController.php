<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\User\StoreOrUpdateUserRequest;
use App\Http\Services\Admin\UserService;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function store(StoreOrUpdateUserRequest $request)
    {
        return $this->service->store($request);
    }

    public function update(StoreOrUpdateUserRequest $request, $id)
    {
        return $this->service->update($request, $id);
    }
}
