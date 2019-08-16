<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\Exceptions\DatabaseConnectionException;
use App\Exceptions\DatabaseQueryException;
use App\FieldSource;
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
        $this->connect();
    }

    public function connect()
    {
        $connection_info = [
            'UID' => $this->dbSource->username,
            'PWD' => $this->dbSource->password,
            'ReturnDatesAsStrings' => true
        ];

        $runner = resolve(SQLServerQueryRunner::class);
        $this->connection = $runner->sqlsrv_connect($this->serverName, $connection_info);
        if ($this->connection === false) {
            Log::error($this->formatErrors($runner->sqlsrv_errors()));
            throw new DatabaseConnectionException('There was a problem in connecting to ' . $this->dbSource->dataSource->name);
        }
        $this->isConnected = TRUE;
    }

    public function executeQuery()
    {
        $runner = resolve(SQLServerQueryRunner::class);
        $resource = $runner->sqlsrv_query($this->connection, $this->fieldsource->query);

        if ($resource === false) {
            Log::error($this->formatErrors($runner->sqlsrv_errors()));
            throw new DatabaseQueryException('There was an error querying the database.');
        }

        $resultSet = [];

        while($row = $runner->sqlsrv_fetch_array($resource, SQLSRV_FETCH_ASSOC)){
            $resultSet [] = $row;
        }

        $runner->sqlsrv_free_stmt($resource);

        $this->close();

        return $resultSet;
    }

    public function close()
    {
        $runner = resolve(SQLServerQueryRunner::class);

        $runner->sqlsrv_close($this->connection);

    }

    /**
     * Return a formatted string with SQL errors.
     * @param $errorArray
     * @return string
     */
    protected function formatErrors($errorArray)
    {
        return collect($errorArray)->map(function($e){
           return collect($e)->only(['SQLSTATE', 'code', 'message'])->transform(function($item, $key) {
              return "{$key}: {$item}";
           })->values()->join(', ');
        })->join('|');

    }
}
