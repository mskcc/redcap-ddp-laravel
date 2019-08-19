<?php

namespace Tests\Unit;

use App\Database\Factories\DataSourceFactory;
use App\DatabaseSource;
use App\DatabaseType;
use App\DataRetrieval\Database\DatabaseConnectionFactory;
use App\DataRetrieval\Database\DB2Connection;
use App\DataRetrieval\Database\MySQLConnection;
use App\DataRetrieval\Database\PostgreSQLConnection;
use App\DataRetrieval\Database\Queries\ConcreteDB2QueryRunner;
use App\DataRetrieval\Database\Queries\ConcreteSQLServerQueryRunner;
use App\DataRetrieval\Database\Queries\DB2QueryRunner;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\DataSource;
use App\FieldSource;
use App\DataRetrieval\Database\SqlServerConnection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseConnectionFactoryTest extends TestCase
{

    use RefreshDatabase;

    private $fieldSource;
    private $databaseSource;
    private $dataSource;

    private function setUpDatabaseSource($dbType)
    {
        $this->dataSource = DataSourceFactory::database($dbType);

        $this->databaseSource = $this->dataSource->source;

        $this->fieldSource = factory(FieldSource::class)->create([
            'name' => 'dob',
            'query' => "SELECT date_of_birth from dbo.patient",
            'data_source_id' => $this->dataSource->id
        ]);

    }

    /** @test */
    function returns_mysql_connection()
    {
        $this->setUpDatabaseSource('mysql');
        $connection = new DatabaseConnectionFactory($this->databaseSource, $this->fieldSource);
        $this->assertInstanceOf(MySQLConnection::class, $connection->createConnection());
    }

    /** @test */
    function returns_sqlserver_connection()
    {
        $this->setUpDatabaseSource('sqlserver');

        if( !defined( "SQLSRV_FETCH_ASSOC" )){
            define( "SQLSRV_FETCH_ASSOC", 2 );
        }
        if( !defined( "SQLSRV_ERR_ALL" )){
            define( "SQLSRV_ERR_ALL", 2 );
        }

        $queryRunner  = \Mockery::mock(ConcreteSQLServerQueryRunner::class);
        $this->app->instance(SQLServerQueryRunner::class, $queryRunner);

        $queryRunner->allows([
            'sqlsrv_connect' => true
        ]);

        $connection = new DatabaseConnectionFactory($this->databaseSource, $this->fieldSource);
        $this->assertInstanceOf(SqlServerConnection::class, $connection->createConnection());
    }

    /** @test */
    function returns_postgresql_connection()
    {
        $this->setUpDatabaseSource('postgresql');
        $connection = new DatabaseConnectionFactory($this->databaseSource, $this->fieldSource);
        $this->assertInstanceOf(PostgreSQLConnection::class, $connection->createConnection());
    }

    /** @test */
    function returns_db2_connection()
    {
        $this->setUpDatabaseSource('db2');

        $queryRunner  = \Mockery::mock(ConcreteDB2QueryRunner::class);
        $this->app->instance(DB2QueryRunner::class, $queryRunner);

        $queryRunner->allows([
            'db2_connect' => true
        ]);

        $connection = new DatabaseConnectionFactory($this->databaseSource, $this->fieldSource);
        $this->assertInstanceOf(DB2Connection::class, $connection->createConnection());
    }


}
