<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\FieldSource;
use Exception;
use Illuminate\Support\Facades\Log;
use function foo\func;

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
            Log::error($e->getMessage());
            Log::error($this->formatErrors($runner->sqlsrv_errors()));
        }
    }

    public function executeQuery()
    {
        $runner = resolve(SQLServerQueryRunner::class);
        $results = [];
        $resource = $runner->sqlsrv_query($this->connection, $this->fieldsource->query);

        if ($resource === false) {
            //Log errors returned by runner
            Log::error($this->formatErrors($runner->sqlsrv_errors()));
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

        $runner->sqlsrv_close();

    }

    /**
     * Return a formatted string with SQL errors.
     * @param $errorArray
     * @return string
     */
    protected function formatErrors($errorArray)
    {
        return collect($errorArray)->transform(function($item, $key) {
            return "{$key}: {$item}";
        })->values()->implode("; ");
    }
}
