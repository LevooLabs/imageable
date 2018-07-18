<?php

namespace LevooLabs\Imageable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

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

        AliasLoader::getInstance()->alias('Image', 'Intervention\Image\Facades\Image');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/imagecache.php', 'imagecache');
        
        $this->app->register('Intervention\Image\ImageServiceProvider');
    }
}
