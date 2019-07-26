<?php

namespace Tests\Unit;

use App\DatabaseSource;
use App\DataRetrieval\Database\DatabaseConnectionFactory;
use App\DataRetrieval\DatabaseQuery;
use App\DataSource;
use App\FieldSource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseConnectionFactoryTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function can_execute_a_database_query()
    {
        $fieldSource = factory(FieldSource::class)->create([
            'name' => 'dob',
            'query' => "SELECT date_of_birth from dbo.patient",
            'data_source' => 'internal_data_warehouse'
        ]);

        $databaseSource = factory(DatabaseSource::class)->create([
            'server' => '127.0.0.1'
        ]);

        $dataSource = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        $dataSource->source()->associate($databaseSource);
        $dataSource->save();

        $query = new DatabaseConnectionFactory($databaseSource, $fieldSource);
        $return = $query->execute();

        $this->assertIsArray($return);
        $this->assertArrayHasKey('field', $return);
        $this->assertArrayHasKey('value', $return);
    }
}
