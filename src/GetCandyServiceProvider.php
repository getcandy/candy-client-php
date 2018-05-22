<?php

namespace GetCandy\Client;

use Illuminate\Support\ServiceProvider;
use GetCandy\Client\Facades\CandyClient;

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
            return new Candy();
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
     * Register helpers file
     */
    public function registerHelpers()
    {
        if (file_exists($file = __DIR__.'/helpers.php'))
        {
            require $file;
        }
    }
}
