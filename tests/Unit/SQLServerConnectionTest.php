<?php

namespace Tests\Unit;

use App\DatabaseSource;
use App\DataRetrieval\Database\SQLServerConnection;
use App\DataSource;
use App\Exceptions\DatabaseConnectionException;
use App\Exceptions\DatabaseQueryException;
use App\FieldSource;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\SqlServerConnection as CoreSqlServerConnection;

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
    /**
     * @var CoreSqlServerConnection|MockInterface
     */
    private $connection;

    protected function setUp() : void
    {
        parent::setUp();
        $this->connection = \Mockery::mock(CoreSqlServerConnection::class);
    }

    /** @test */
    function can_run_queries()
    {
        $this->setUpDatabaseSource([
            'server' => '127.0.0.1',
            'port' => 999
        ], "SELECT * FROM TEST_TABLE;");

        DB::shouldReceive('connection')->andReturn($this->connection);
        $this->connection->shouldReceive('select')->with('SELECT * FROM TEST_TABLE;')
            ->andReturn([
                    (object)['fakecolumn' => '12345']
                ]
            );

        $sqlServerConnection = new SQLServerConnection($this->databaseSource, $this->fieldSource);

        $results = $sqlServerConnection->executeQuery();

        $this->assertEquals(12345, $results[0]->fakecolumn);
    }

    /** @test */
    function it_will_log_errors_if_sql_statement_fails()
    {
        $this->setUpDatabaseSource([
            'server' => '127.0.0.1',
            'port' => 999
        ], "SELECT * FROM TEST_TABLE;");

        DB::shouldReceive('connection')->andReturn($this->connection);

        $this->connection->shouldReceive('select')->with('SELECT * FROM TEST_TABLE;')
            ->andThrow(new \Exception('A BAD THING HAPPENED!'));

        $sqlServerConnection = new SQLServerConnection($this->databaseSource, $this->fieldSource);

        $sqlServerConnection->executeQuery();

        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();

        $this->assertCount(1, $records);
        $this->assertEquals(
            'A BAD THING HAPPENED!',
            $records[0]['message']
        );

    }


    private function setUpDatabaseSource(array $overrides, $query = null)
    {
        $this->databaseSource = factory(DatabaseSource::class)->create($overrides);
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
    }
}
