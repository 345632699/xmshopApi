<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Client\ClientRepositoryInterface::class,
            \App\Repositories\Client\ClientRepository::class
        );
        $this->app->bind(
            \App\Repositories\Pay\PayRepositoryInterface::class,
            \App\Repositories\Pay\PayRepository::class
        );
        $this->app->bind(
            \App\Repositories\Good\GoodRepositoryInterface::class,
            \App\Repositories\Good\GoodRepository::class
        );
        $this->app->bind(
            \App\Repositories\Order\OrderRepositoryInterface::class,
            \App\Repositories\Order\OrderRepository::class
        );
    }
}
