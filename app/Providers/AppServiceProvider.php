<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Log;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Raven_Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
        }

        // Sentry logs
        $client = new Raven_Client(config('sentry.dsn'));
        $handler = new RavenHandler($client);
        $handler->setFormatter(new LineFormatter("%message% %context% %extra%\n"));

        Log::getMonolog()->pushHandler($handler);
    }
}
