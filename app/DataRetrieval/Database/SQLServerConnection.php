<?php


namespace App\DataRetrieval\Database;
use App\DatabaseSource;
use App\FieldSource;

class SQLServerConnection extends DatabaseConnectionBase
{

    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        parent::__construct($dbsource, $fieldsource);

        config(['database.connections' => [
            $this->dbSource->dataSource->name => [
                'driver' => 'sqlsrv',
                'host' => $this->dbSource->server,
                'port' => $this->dbSource->port,
                'database' => $this->dbSource->db_name,
                'username' => $this->dbSource->username,
                'password' => $this->dbSource->password,
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
            ]
        ]]);
    }
}
