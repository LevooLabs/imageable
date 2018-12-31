<?php

namespace LevooLabs\Imageable;

use LevooLabs\Imageable\ImageFilters\Basic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Config\Repository as Config;

class ImageableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Config $config)
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/images/default.jpg' => public_path('images/imageable/default.jpg')
            ]);
        }

        $config->push('imagecache.paths', storage_path('app/uploads'));
        $config->push('imagecache.paths', public_path('images/imageable'));
        
        #NOTE default image filters
        if (!$config->has('imagecache.templates.default')) {
            $config->set('imagecache.templates.default', Basic\Upload::class);
            $config->set('imagecache.templates.default-s', Basic\Small::class);
            $config->set('imagecache.templates.default-m', Basic\Upload::class);
            $config->set('imagecache.templates.default-l', Basic\Upload::class);
        }
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
