<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Warn developer when database access occurs via lazy loading
        Model::preventLazyLoading($this->app->environment('local'));
        // Warn developer when mass-filling a non-fillable DB model attribute to avoid sneaky update bugs
        Model::preventSilentlyDiscardingAttributes($this->app->environment('local'));
    }
}
