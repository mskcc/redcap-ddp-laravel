<?php

namespace App\Providers;

use App\DataRetrieval\Database\Queries\ConcreteSQLServerQueryRunner;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SQLServerQueryRunner::class,
            ConcreteSQLServerQueryRunner::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
