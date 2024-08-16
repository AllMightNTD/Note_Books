<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DishController;
use App\Http\Controllers\Api\User\DishUser;
use App\Http\Controllers\Api\Admin\FileController;
use App\Http\Controllers\Api\Admin\OpeningHourController;
use App\Http\Controllers\Api\Admin\RestaurantController;
use App\Http\Controllers\Api\Admin\SubCategoryController;
use App\Http\Controllers\Api\User\RestaurantUserController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\DishHomeController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\SendMessage;
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

// User
Route::namespace('App\Http\Controllers\Api\User')->prefix('/user')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('reset-password', 'AuthController@resetPassword');
    Route::post('refresh-token', 'AuthController@refreshToken');
    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', 'AuthController@me');
        Route::post('logout', 'AuthController@logout');
        Route::get('information', 'AuthController@information');
        Route::put('information-update', 'AuthController@updateInformation');
        Route::put('change-password', 'AuthController@changePassword');
    });
});

// Home
Route::middleware(['auth:api'])->group(function () {
    Route::get('/dish-home/{id}', [DishHomeController::class, 'show']);
    Route::get('/dish-home', [DishHomeController::class, 'allDishHome']);
    Route::get('/area-all', [AreaController::class, 'index']);
    Route::get('/search', [DishHomeController::class, 'searchDish']);
    Route::post('/reservation', [ReservationController::class, 'store']);
    Route::get('/reservation-history', [ReservationController::class, 'historyReservation']);
    Route::get('restaurants', [RestaurantController::class, 'queryByCategory']);
    Route::post('/send-message', [SendMessage::class, 'sendMessage']);
    Route::get('/messages', [PusherController::class, 'index']);
    Route::get('/broadcast', [PusherController::class, 'broadcast']);
    Route::get('/receive', [PusherController::class, 'receive']);
    Route::post('/user-update-reservation/{id}', [ReservationController::class, 'updateUserReservation']);
});

// Admin
Route::namespace('App\Http\Controllers\Api\Admin')->prefix('/admin')->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::apiResource('categories', CategoryController::class);
        Route::prefix('key-value')->group(function () {
            Route::get('categories', [CategoryController::class, 'getAllCategoriesKeyValue']);
            Route::get('sub_categories', [SubCategoryController::class, 'getAllSubCategories']);
        });
        Route::apiResource('users', UserController::class);
        Route::middleware(['role:manager'])->group(function () {
            Route::get('restaurants', 'RestaurantController@index');
            Route::get('restaurants/{id}', 'RestaurantController@show');
            Route::post('restaurants', 'RestaurantController@store');
            Route::post('restaurants/{id}/up-date', 'RestaurantController@update');
            Route::apiResource('file', FileController::class);
            Route::post('dishs', 'DishController@store');
            Route::get('dishs/{id}', 'DishController@show');
            Route::post('dishs/{id}/up-date', 'DishController@update');
            Route::apiResource('opening-hours', OpeningHourController::class);
            Route::get('/reservation-history-manager', [ReservationController::class, 'historyReservationManager']);
            Route::get('/reservation-history-manager/{id}', [ReservationController::class, 'historyReservationDetail']);
            Route::put('/reservation-history-manager/{id}/up-date', [ReservationController::class, 'updateReservation']);

            Route::prefix('key-value')->group(function () {
                Route::get('restaurants', [RestaurantController::class, 'getAllRestaurantKeyValue']);
            });
        });
    });
});
