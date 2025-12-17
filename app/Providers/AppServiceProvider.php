<?php

namespace App\Providers;

use App\Application\ShippingLabel\Services\ShippingProviderManager;
use App\Domain\ShippingLabel\Repositories\ShippingLabelRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\ShippingLabel\Mappers\ShippingLabelMapper;
use App\Infrastructure\ShippingLabel\Repositories\EloquentShippingLabelRepository;
use App\Infrastructure\ShippingLabel\Services\EasyPostService;
use App\Infrastructure\User\Mappers\UserMapper;
use App\Infrastructure\User\Repositories\EloquentUserRepository;
use App\Models\ShippingLabel;
use App\Models\User;
use App\Policies\ShippingLabelPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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

        $this->app->singleton(ShippingProviderManager::class, function ($app) {
            $manager = new ShippingProviderManager();

            $manager->register(new EasyPostService());

            return $manager;
        });
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(ShippingLabel::class, ShippingLabelPolicy::class);
    }
}
