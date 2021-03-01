<?php

namespace NovaListCard;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use NovaListCard\Console\ListCardCommand;

class CardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('list-card', __DIR__.'/../dist/js/card.js');
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                ListCardCommand::class,
            ]);
        }
    }

    /**
     * Register the card's routes.
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
                ->prefix('nova-vendor/nova-list-card')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
