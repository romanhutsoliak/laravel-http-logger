<?php

namespace Hutsoliak\HttpLogger\Providers;

use Hutsoliak\HttpLogger\Middleware\HttpLogger;
use Hutsoliak\HttpLogger\Storage\ListenerResponseStorage;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class HttpLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->singleton(ListenerResponseStorage::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $kernel = resolve(Kernel::class);
        $kernel->pushMiddleware(HttpLogger::class);
    }
}
