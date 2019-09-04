<?php

namespace App\DataRetrieval\Database;
use App\DatabaseSource;
use App\FieldSource;
use PDO;

class MySQLConnection extends DatabaseConnectionBase
{
    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        parent::__construct($dbsource, $fieldsource);

        config(['database.connections' => [
            $this->dbSource->dataSource->name => [
                'driver' => 'mysql',
                'host' => $this->dbSource->server,
                'port' => $this->dbSource->port,
                'database' => $this->dbSource->db_name,
                'username' => $this->dbSource->username,
                'password' => $this->dbSource->password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ]
        ]]);
    }

}
