<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\DataRetrieval\Database\Queries\ConcreteDB2QueryRunner;
use App\DataRetrieval\Database\Queries\DB2QueryRunner;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\Exceptions\DatabaseConnectionException;
use App\FieldSource;
use Illuminate\Support\Facades\Log;

class DB2Connection extends DatabaseConnectionBase
{

    /**
     * @var string
     */
    private $connectionString;

    /**
     * @var false|resource
     */
    private $connection;
    /**
     * @var bool
     */
    private $isConnected;

    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        parent::__construct($dbsource, $fieldsource);
        $this->connectionString = "DRIVER={IBM DB2 ODBC DRIVER};DATABASE={$this->dbSource->db_name};" .
            "HOSTNAME={$this->dbSource->server};PORT={$this->dbSource->port};PROTOCOL=TCPIP;UID={$this->dbSource->username};PWD={$this->dbSource->password};";

        $this->connect();
    }

    public function connect()
    {
        $runner = resolve(DB2QueryRunner::class);

        $this->connection = $runner->db2_connect($this->connectionString, '', '');

        if ($this->connection === false) {
            Log::error($this->formatErrors($runner->db2_conn_errormsg()));
            throw new DatabaseConnectionException('There was a problem in connecting to ' . $this->dbSource->dataSource->name);
        }
        $this->isConnected = TRUE;
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
