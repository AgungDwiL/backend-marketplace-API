<?php

namespace App\Providers;

use App\Repositories\CheckoutRepository;
use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\RepositoryInterfaces\VendorRepositoryInterface;
use App\Repositories\VendorRepository;
use App\Repositories\RepositoryInterfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\RepositoryInterfaces\CheckoutRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            VendorRepositoryInterface::class,
            VendorRepository::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            CheckoutRepositoryInterface::class,
            CheckoutRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
