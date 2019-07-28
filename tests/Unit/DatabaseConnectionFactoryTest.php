<?php

namespace Tests\Unit;

use App\DatabaseSource;
use App\DatabaseType;
use App\DataRetrieval\Database\DatabaseConnectionFactory;
use App\DataRetrieval\Database\DB2Connection;
use App\DataRetrieval\Database\MySQLConnection;
use App\DataRetrieval\Database\PostgreSQLConnection;
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
        $this->fieldSource = factory(FieldSource::class)->create([
            'name' => 'dob',
            'query' => "SELECT date_of_birth from dbo.patient",
            'data_source' => 'internal_data_warehouse'
        ]);

        $this->databaseSource = factory(DatabaseSource::class)->create([
            'db_type' => factory(DatabaseType::class)->create(['name' => $dbType]),
        ]);

        $this->dataSource = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        $this->dataSource->source()->associate($this->databaseSource);
        $this->dataSource->save();
    }

    /** @test */
    function returns_mysql_connection()
    {
        $this->setUpDatabaseSource('mysql');
        $connection = new DatabaseConnectionFactory($this->databaseSource, $this->fieldSource);
        $this->assertInstanceOf(MySQLConnection::class, $connection->getConnection());
    }

    /** @test */
    function returns_sqlserver_connection()
    {
        $this->setUpDatabaseSource('sqlserver');
        $connection = new DatabaseConnectionFactory($this->databaseSource, $this->fieldSource);
        $this->assertInstanceOf(SqlServerConnection::class, $connection->getConnection());
    }

    /** @test */
    function returns_postgresql_connection()
    {
        $this->setUpDatabaseSource('postgresql');
        $connection = new DatabaseConnectionFactory($this->databaseSource, $this->fieldSource);
        $this->assertInstanceOf(PostgreSQLConnection::class, $connection->getConnection());
    }

    /** @test */
    function returns_db2_connection()
    {
        $this->setUpDatabaseSource('db2');
        $connection = new DatabaseConnectionFactory($this->databaseSource, $this->fieldSource);
        $this->assertInstanceOf(DB2Connection::class, $connection->getConnection());
    }
}
