<?php
namespace App\Factories;

use App\DatabaseSource;
use App\DataSource;
use App\FieldSource;
use App\ProjectMetadata;

class DatabaseSourceFactory
{
    public static function type($dbType)
    {
        factory(ProjectMetadata::class)->create([
            'project_id' => 12345,
            'field' => 'birth_date',
            'label' => 'Subject Birth Date',
            'dictionary' => 'dob'
        ]);

        factory(FieldSource::class)->create([
            'name' => 'dob',
            'query' => "SELECT date_of_birth from dbo.patient",
            'data_source' => 'internal_data_warehouse'
        ]);

        $databaseSource = factory(DatabaseSource::class)->state($dbType)->create([
            'server' => '127.0.0.1'
        ]);

        $dataSource = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        $dataSource->source()->associate($databaseSource);

        $dataSource->save();

        return $databaseSource;

    }
}
