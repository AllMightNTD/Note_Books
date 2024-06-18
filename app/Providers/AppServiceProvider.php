<?php

namespace App\Providers;

use App\Repositories\Interfaces\AuthInterface;
use App\Repositories\Interfaces\CategoryInterface;
use App\Repositories\Interfaces\DishInterface;
use App\Repositories\Interfaces\InformationInterface;
use App\Repositories\Interfaces\RestaurantInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Repository\Admin\CategoryRepository;
use App\Repositories\Repository\Admin\DishRepository;
use App\Repositories\Repository\Admin\InformationRepository;
use App\Repositories\Repository\Admin\RestaurantRepository;
use App\Repositories\Repository\Admin\UserRepository;
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
        $this->app->bind(CategoryInterface::class , CategoryRepository::class);
        $this->app->bind(DishInterface::class , DishRepository::class);
        $this->app->bind(UserInterface::class , UserRepository::class);
        $this->app->bind(InformationInterface::class , InformationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
