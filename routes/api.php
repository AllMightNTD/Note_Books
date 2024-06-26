<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DishController;
use App\Http\Controllers\Api\Admin\FileController;
use App\Http\Controllers\Api\Admin\OpeningHourController;
use App\Http\Controllers\Api\Admin\RestaurantController;
use App\Http\Controllers\Api\Admin\UserController;
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

Route::namespace('App\Http\Controllers\Api\User')->prefix('/user')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('reset-password', 'AuthController@resetPassword');
    Route::post('refresh-token' , 'AuthController@refreshToken');
    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', 'AuthController@me');
        Route::post('logout', 'AuthController@logout');
        Route::get('information', 'AuthController@information');
        Route::put('information-update' , 'AuthController@updateInformation');
        Route::put('change-password', 'AuthController@changePassword');
    });
});

Route::namespace('App\Http\Controllers\Api\Admin')->prefix('/admin')->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::apiResource('categories' , CategoryController::class);
        Route::prefix('key-value')->group(function () {
            Route::get('categories', [CategoryController::class, 'getAllCategoriesKeyValue']);
        });
        Route::middleware(['role:admin'])->group(function () {
            Route::apiResource('users', UserController::class);
            Route::get('restaurants', 'RestaurantController@index');
            Route::post('restaurants', 'RestaurantController@store');
            Route::post('restaurants/{id}', 'RestaurantController@update');
            Route::get('restaurants/{id}', 'RestaurantController@show');       
            Route::apiResource('file' , FileController::class);
            Route::apiResource('dishs' , DishController::class);
            Route::apiResource('opening-hours' , OpeningHourController::class);

            Route::prefix('key-value')->group(function () {
                Route::get('restaurants', [RestaurantController::class, 'getAllRestaurantKeyValue']);
            });
        });
    });
});

