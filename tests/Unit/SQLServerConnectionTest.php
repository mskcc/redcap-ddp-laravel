<?php

namespace Tests\Unit;

use App\DatabaseSource;
use App\DataRetrieval\Database\DatabaseConnectionFactory;
use App\DataRetrieval\Database\Queries\ConcreteSQLServerQueryRunner;
use App\DataRetrieval\Database\Queries\FakeSQLServerQueryRunner;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\DataRetrieval\Database\SQLServerConnection;
use App\DataSource;
use App\FieldSource;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SQLServerConnectionTest extends TestCase
{
    use RefreshDatabase;

    private $fieldSource;
    private $databaseSource;
    private $dataSource;

    /**
     * @var MockInterface
     */
    private $queryRunner;

    protected function setUp() : void
    {
        parent::setUp();
        if( !defined( "SQLSRV_FETCH_ASSOC" )){
            define( "SQLSRV_FETCH_ASSOC", 2 );
        }
        $this->queryRunner = \Mockery::mock(ConcreteSQLServerQueryRunner::class);
        $this->app->instance(SQLServerQueryRunner::class, $this->queryRunner);
    }

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

    /** @test */
    function can_run_queries()
    {
        $this->setUpDatabaseSource([
            'server' => '127.0.0.1',
            'port' => 999
        ]);

        $this->queryRunner->allows([
            'sqlsrv_query' => null,
            'sqlsrv_fetch_array' => []
        ]);

        $sqlServerConnection = new SQLServerConnection($this->databaseSource, $this->fieldSource);
        $sqlServerConnection->executeQuery();
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
