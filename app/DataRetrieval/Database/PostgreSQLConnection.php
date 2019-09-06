<?php

namespace App\DataRetrieval\Database;
use App\DatabaseSource;
use App\FieldSource;

class PostgreSQLConnection extends DatabaseConnectionBase
{
    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        parent::__construct($dbsource, $fieldsource);

        config(['database.connections' => [
            $this->dbSource->dataSource->name => [
                'driver' => 'pgsql',
                'host' => $this->dbSource->server,
                'port' => $this->dbSource->port,
                'database' => $this->dbSource->db_name,
                'username' => $this->dbSource->username,
                'password' => $this->dbSource->password,
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer'
            ]
        ]]);

    }
}
