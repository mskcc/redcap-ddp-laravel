<?php

namespace Tests\Unit;

use App\DatabaseSource;
use App\DataRetrieval\Database\DatabaseConnectionFactory;
use App\DataRetrieval\Database\SQLServerConnection;
use App\DataSource;
use App\FieldSource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SQLServerConnectionTest extends TestCase
{
    use RefreshDatabase;

    private $fieldSource;
    private $databaseSource;
    private $dataSource;

    /** @test */
    function formats_server_properly_if_port_is_not_provided()
    {
        $this->setUpDatabaseSource([
            'server' => '127.0.0.1',
            'port' => null
        ]);

        $sqlServerConnection = new SQLServerConnection($this->databaseSource, $this->fieldSource);

        $this->assertEquals('127.0.0.1', $sqlServerConnection->serverName);

        $this->assertNull($this->databaseSource->port);

    }

    /** @test */
    function formats_server_properly_if_port_is_provided()
    {
        $this->setUpDatabaseSource([
            'server' => '127.0.0.1',
            'port' => 999
        ]);

        $sqlServerConnection = new SQLServerConnection($this->databaseSource, $this->fieldSource);

        $this->assertEquals('127.0.0.1, 999', $sqlServerConnection->serverName);

        $this->assertEquals(999, $this->databaseSource->port);

    }

    private function setUpDatabaseSource(array $overrides)
    {
        $this->fieldSource = factory(FieldSource::class)->create([
            'name' => 'dob',
            'query' => "SELECT date_of_birth from dbo.patient",
            'data_source' => 'internal_data_warehouse'
        ]);

        $this->databaseSource = factory(DatabaseSource::class)->create($overrides);
        $this->dataSource = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        $this->dataSource->source()->associate($this->databaseSource);
        $this->dataSource->save();
    }
}
