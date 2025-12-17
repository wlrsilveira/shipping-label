<?php

namespace App\Providers;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\User\Mappers\UserMapper;
use App\Infrastructure\User\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepositoryInterface::class, function ($app) {
            return new EloquentUserRepository(
                new UserMapper()
            );
        });

        $this->app->singleton(EloquentUserRepository::class, function ($app) {
            return new EloquentUserRepository(
                new UserMapper()
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
