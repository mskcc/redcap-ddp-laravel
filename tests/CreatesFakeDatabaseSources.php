<?php


namespace Tests;


use App\DatabaseSource;
use App\DataSource;
use App\FieldSource;

trait CreatesFakeDatabaseSources
{

    private function setUpDatabaseSource(array $overrides, $query = null, $type = 'sqlserver')
    {
        $this->databaseSource = factory(DatabaseSource::class)->state($type)->create($overrides);

        $this->dataSource = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        $this->dataSource->source()->associate($this->databaseSource);
        $this->dataSource->save();

        $this->fieldSource = factory(FieldSource::class)->create([
            'name' => 'dob',
            'query' => $query ?? "SELECT date_of_birth from dbo.patient",
            'data_source_id' => $this->dataSource->id
        ]);

        return $this;

    }

}
