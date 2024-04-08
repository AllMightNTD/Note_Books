<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

$router->namespace('App\Http\Controllers\Cms')->prefix('/cms')->group(function ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->post('/reset-password', 'AuthController@resetPassword');
    $router->post('/refresh-token' , 'AuthController@refreshToken');
    $router->middleware(['auth:api'])->group(function ($router) {
        $router->get('user', 'AuthController@index');
        $router->get('profile', 'AuthController@me');
        $router->post('logout', 'AuthController@logout');
        $router->delete('user/{id}', 'AuthController@destroy');
    });
});

