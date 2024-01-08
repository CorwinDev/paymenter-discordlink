<?php

namespace CorwinDev\PaymenterDiscordLink;

use Illuminate\Support\ServiceProvider;
use CorwinDev\PaymenterDiscordLink\Console\Commands\DiscordLink;

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
