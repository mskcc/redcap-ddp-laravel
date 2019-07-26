<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\FieldSource;
use Exception;

class SQLServerConnection extends DatabaseConnectionBase
{
    /**
     * @var false|resource
     */
    public $connection;
    /**
     * @var bool
     */
    private $isConnected;

    public $serverName;

    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        parent::__construct($dbsource, $fieldsource);
        $this->connect();
    }

    public function connect()
    {
        $connection_info = [
            'UID' => $this->dbSource->username,
            'PWD' => $this->dbSource->password,
            'ReturnDatesAsStrings' => true
        ];

        $this->serverName = $this->dbSource->port == null ? $this->dbSource->server : "{$this->dbSource->server}, {$this->dbSource->port}";

        try {
            $this->connection = sqlsrv_connect($this->dbSource->server, $connection_info);
            if (!$this->connection) {
                throw new Exception('There was a problem in connecting to ' . $this->dbSource->dataSource->name);
            }
            $this->isConnected = TRUE;
        }

        catch (Exception $e) {
            $this->isConnected = FALSE;
            //Log Exception and SQL Server Errors
        }
    }

    public function executeQuery()
    {
        // TODO: Implement executeQuery() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}