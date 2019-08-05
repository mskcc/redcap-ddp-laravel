<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\FieldSource;
use Exception;
use Illuminate\Support\Facades\Log;

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
        $this->serverName = $this->dbSource->port == null ? $this->dbSource->server : "{$this->dbSource->server}, {$this->dbSource->port}";
    }

    public function connect()
    {
        $connection_info = [
            'UID' => $this->dbSource->username,
            'PWD' => $this->dbSource->password,
            'ReturnDatesAsStrings' => true
        ];

        try {

            $runner = resolve(SQLServerQueryRunner::class);

            $this->connection = $runner->sqlsrv_connect($this->serverName, $connection_info);
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
        $runner = resolve(SQLServerQueryRunner::class);
        $results = [];
        $resource = $runner->sqlsrv_query($this->connection, $this->fieldsource->query);

        if ($resource === false) {
            //Log errors returned by runner
        }

        while ($resultSet = $runner->sqlsrv_fetch_array($resource, SQLSRV_FETCH_ASSOC)){

            $results [] = [
                'value' => $resultSet
            ];

            $runner->sqlsrv_free_stmt($resource);
        }

        $this->close();

    }

    public function close()
    {
        $runner = resolve(SQLServerQueryRunner::class);

        //Close the connection

    }
}
