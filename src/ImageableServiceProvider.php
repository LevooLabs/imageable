<?php

namespace LevooLabs\Imageable;

use Illuminate\Support\ServiceProvider;

class ImageableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->publishes([
            __DIR__.'/../resources/images/default.jpg' => public_path('images/imageable/default.jpg')
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/imagecache.php', 'imagecache');
    }
}
