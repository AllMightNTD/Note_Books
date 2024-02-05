<?php

namespace App\Providers;

use App\Repositories\Interfaces\AuthInterface;
use App\Repositories\Repository\AuthRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
