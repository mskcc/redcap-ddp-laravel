<?php

namespace Tests\Unit;

use App\DatabaseSource;
use App\DataRetrieval\Database\DatabaseConnectionFactory;
use App\DataRetrieval\Database\Queries\ConcreteSQLServerQueryRunner;
use App\DataRetrieval\Database\Queries\FakeSQLServerQueryRunner;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\DataRetrieval\Database\SQLServerConnection;
use App\DataSource;
use App\Exceptions\DatabaseConnectionException;
use App\Exceptions\DatabaseQueryException;
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
        if( !defined( "SQLSRV_ERR_ALL" )){
            define( "SQLSRV_ERR_ALL", 2 );
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

        $this->queryRunner->allows([
            'sqlsrv_connect' => true
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

        $this->queryRunner->allows([
            'sqlsrv_connect' => true
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
            'sqlsrv_connect' => true,
            'sqlsrv_query' => null,
            'sqlsrv_fetch_array' => [
                'date_of_birth' => '1950-01-01'
            ],
            'sqlsrv_close' => true,
            'sqlsrv_free_stmt' => true
        ]);

        $sqlServerConnection = new SQLServerConnection($this->databaseSource, $this->fieldSource);

        $result = $sqlServerConnection->executeQuery();

    }

    /** @test */
    function it_will_log_errors_if_sql_statement_fails()
    {
        $this->expectException(DatabaseQueryException::class);
        $this->setUpDatabaseSource([
            'server' => '127.0.0.1',
            'port' => 999
        ]);

        $this->queryRunner->allows([
            'sqlsrv_connect' => true,
            'sqlsrv_query' => false,
            'sqlsrv_fetch_array' => [],
            'sqlsrv_errors' => [
                'SQLSTATE' => 'INVALID',
                'code' => 123,
                'message' => 'An error occurred.'
            ],
            'sqlsrv_close' => true
        ]);

        $sqlServerConnection = new SQLServerConnection($this->databaseSource, $this->fieldSource);
        $sqlServerConnection->executeQuery();

        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();

        $this->assertCount(1, $records);
        $this->assertEquals(
            'SQLSTATE: INVALID; code: 123; message: An error occurred.',
            $records[0]['message']
        );

    }

    /** @test */
    function it_will_log_errors_if_sql_connection_fails()
    {
        try {
            $this->setUpDatabaseSource([
                'server' => '127.0.0.1',
                'port' => 999
            ]);

            $this->queryRunner->allows([
                'sqlsrv_connect' => false,
                'sqlsrv_errors' => [
                    [
                        0 => 'HYT00',
                        'SQLSTATE' => 'HYT00',
                        1 => 0,
                        'code' => 0,
                        2 => '[Microsoft][ODBC Driver 17 for SQL Server]Login timeout expired',
                        'message' => '[Microsoft][ODBC Driver 17 for SQL Server]Login timeout expired'
                    ],
                    [
                        0 => '08001',
                        'SQLSTATE' => '08001',
                        1 => 10057,
                        'code' => 10057,
                        2 => '[Microsoft][ODBC Driver 17 for SQL Server]TCP Provider: Error code 0x2749',
                        'message' => '[Microsoft][ODBC Driver 17 for SQL Server]TCP Provider: Error code 0x2749'
                    ]
                ]
            ]);

            new SQLServerConnection($this->databaseSource, $this->fieldSource);

        } catch (DatabaseConnectionException $e) {
            $records = app('log')
                ->getHandlers()[0]
                ->getRecords();


            $this->assertCount(1, $records);
            $this->assertEquals(
                'SQLSTATE: HYT00, code: 0, message: [Microsoft][ODBC Driver 17 for SQL Server]Login timeout expired|SQLSTATE: 08001, code: 10057, message: [Microsoft][ODBC Driver 17 for SQL Server]TCP Provider: Error code 0x2749',
                $records[0]['message']);
        }


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
