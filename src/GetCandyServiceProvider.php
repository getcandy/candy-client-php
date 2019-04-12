<?php

namespace GetCandy\Client;

use GetCandy\Client\Auth\CandyGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use GetCandy\Client\Facades\CandyClient;
use GetCandy\Client\Auth\CandyUserProvider;

class GetCandyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(CandyClient::class, function ($app) {
            return new Candy($app);
        });

        $this->app->bind(CandyClientManager::class, function ($app) {
            return new CandyClientManager($app);
        });

        $this->app['auth']->extend('candy', function ($app, $name, array $config) {
            return new CandyGuard(
                'getcandy',
                $this->app->make(CandyUserProvider::class),
                $app['session.store'],
                $app->request
            );
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register helpers file.
     */
    public function registerHelpers()
    {
        if (file_exists($file = __DIR__.'/helpers.php')) {
            require $file;
        }
    }
}
