<?php

namespace App\Providers;

use App\Models\Expediente;
use App\Observers\ExpedienteObserver;
use Illuminate\Support\ServiceProvider;

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
        Expediente::observe(ExpedienteObserver::class);
    }
}
