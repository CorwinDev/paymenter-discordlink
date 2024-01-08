<?php

namespace CorwinDev\PaymenterDiscordlink;

use Illuminate\Support\ServiceProvider;
use CorwinDev\PaymenterDiscordlink\Console\Commands\DiscordLink;

class PaymenterDiscordLinkServiceProvider extends ServiceProvider
{
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                DiscordLink::class,
            ]);
        }
    }
}