<?php

namespace App\Providers;

use App\Domain\ShippingLabel\Repositories\ShippingLabelRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\ShippingLabel\Mappers\ShippingLabelMapper;
use App\Infrastructure\ShippingLabel\Repositories\EloquentShippingLabelRepository;
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

        $this->app->singleton(ShippingLabelRepositoryInterface::class, function ($app) {
            return new EloquentShippingLabelRepository(
                new ShippingLabelMapper()
            );
        });

        $this->app->singleton(EloquentShippingLabelRepository::class, function ($app) {
            return new EloquentShippingLabelRepository(
                new ShippingLabelMapper()
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
