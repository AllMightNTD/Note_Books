<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\User\InformationRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Services\User\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function resetPassword(Request $request){
        return $this -> service -> resetPassword($request);
    }    

    public function information(Request $request){
        return $this->service->information($request);
    }


    public function updateInformation(InformationRequest $request){
        return $this->service->updateInformation($request);
    }

    public function changePassword(Request $request){
        return $this->service->changePassword($request);
    }

    public function refreshToken(Request $request){
        return $this->service->refreshToken($request);
    }
}
