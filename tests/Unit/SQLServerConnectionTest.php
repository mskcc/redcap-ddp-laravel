<?php

namespace Tests\Unit;

use App\DataRetrieval\Database\SQLServerConnection;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use Tests\CreatesFakeDatabaseSources;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\SqlServerConnection as CoreSqlServerConnection;

class SQLServerConnectionTest extends TestCase
{
    use RefreshDatabase, CreatesFakeDatabaseSources;

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

        $fakeId = '12345';

        DB::shouldReceive('connection')->andReturn($this->connection);
        $this->connection->shouldReceive('select')->with('SELECT * FROM TEST_TABLE;')
            ->andReturn([
                    (object)['fakecolumn' => '12345']
                ]
            );

        $sqlServerConnection = new SQLServerConnection($this->databaseSource, $this->fieldSource);

        $results = $sqlServerConnection->executeQuery($fakeId);

        $this->assertEquals(12345, $results[0]->fakecolumn);
    }

    /** @test */
    function it_will_log_errors_if_sql_statement_fails()
    {
        $this->setUpDatabaseSource([
            'server' => '127.0.0.1',
            'port' => 999
        ], "SELECT * FROM TEST_TABLE;");

        $fakeId = '12345';

        DB::shouldReceive('connection')->andReturn($this->connection);

        $this->connection->shouldReceive('select')->with('SELECT * FROM TEST_TABLE;')
            ->andThrow(new \Exception('A BAD THING HAPPENED!'));

        $sqlServerConnection = new SQLServerConnection($this->databaseSource, $this->fieldSource);

        $sqlServerConnection->executeQuery($fakeId);

        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();

        $this->assertCount(1, $records);
        $this->assertEquals(
            'A BAD THING HAPPENED!',
            $records[0]['message']
        );

    }

}
