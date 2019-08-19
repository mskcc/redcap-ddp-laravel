<?php

namespace App\Database\Factories;

use App\DatabaseSource;
use App\DatabaseType;
use App\DataSource;

class DataSourceFactory
{
    public static function database($dbType = 'sqlserver')
    {
        $databaseSource = factory(DatabaseSource::class)->create([
            'db_type' => DatabaseType::where('name', $dbType)->first()
        ]);

        $dataSource = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        $dataSource->source()->associate($databaseSource);
        $dataSource->save();
        return $dataSource;
    }
}
