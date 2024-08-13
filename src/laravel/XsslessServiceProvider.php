<?php

namespace Medilies\Xssless\Laravel;

use Illuminate\Support\ServiceProvider;
use Medilies\Xssless\Laravel\Commands\SetupCommand;
use Medilies\Xssless\Laravel\Commands\StartCommand;
use Medilies\Xssless\Xssless;

class XsslessServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/xssless.php', 'xssless');

        $this->app->bind(
            Xssless::class,
            fn () => (new Xssless)->usingLaravelConfig()
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/xssless.php' => config_path('xssless.php'),
        ], 'xssless-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupCommand::class,
                StartCommand::class,
            ]);
        }
    }
}
