<?php

namespace App;

class DatabaseSource extends DataSource
{

    public $table = 'data_sources';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('database', function (Builder $builder) {
            $builder->where('type', 'database');
        });
    }
}
