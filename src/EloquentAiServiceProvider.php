<?php

namespace Antley\EloquentAi;

use Illuminate\Support\ServiceProvider;

class EloquentAiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('eloquent-ai.php'),
            ], 'config');
        }
    }

    /**
     * Register the application.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'eloquent-ai');
    }
}
