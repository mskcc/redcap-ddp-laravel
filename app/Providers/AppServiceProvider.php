<?php

namespace App\Providers;

use App\DataRetrieval\Database\Queries\ConcreteSQLServerQueryRunner;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\DataRetrieval\DataGateway;
use App\DataRetrieval\DataGatewayInterface;
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

        $this->app->bind(
            DataGatewayInterface::class,
            DataGateway::class
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
