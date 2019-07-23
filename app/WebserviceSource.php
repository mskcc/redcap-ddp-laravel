<?php

namespace App;

class WebserviceSource extends DataSource
{
    public $table = 'data_sources';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('webservice', function (Builder $builder) {
            $builder->where('type', 'webservice');
        });
    }
}
