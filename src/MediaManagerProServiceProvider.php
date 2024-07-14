<?php

namespace Vendor\MediaManagerPro;

use Illuminate\Support\ServiceProvider;

class MediaManagerProServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('media-manager-pro', function ($app) {
            return new MediaManagerPro();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}
