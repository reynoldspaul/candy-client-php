<?php

namespace GetCandy\Client;

use Illuminate\Support\ServiceProvider;

class GetCandyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('getcandy', function ($app) {
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
}
