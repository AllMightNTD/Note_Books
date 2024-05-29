<?php

namespace App\Providers;

use App\Repositories\Interfaces\AuthInterface;
use App\Repositories\Interfaces\RestaurantInterface;
use App\Repositories\Repository\Admin\RestaurantRepository;
use App\Repositories\Repository\User\AuthRepository as UserAuthRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(AuthInterface::class, UserAuthRepository::class);
        $this->app->bind(RestaurantInterface::class, RestaurantRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
