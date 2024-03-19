<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Services\User\AuthService;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        return $this->service->register($request);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me(Request $request)
    {
        return $this->service->me($request);
    }

    public function login(LoginRequest $request)
    {
        return $this->service->login($request);
    }

    public function refresh(Request $request){
        return $this -> service -> refresh($request);
    }    
}
