<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\DataRetrieval\Database\Queries\ConcreteDB2QueryRunner;
use App\DataRetrieval\Database\Queries\DB2QueryRunner;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\Exceptions\DatabaseConnectionException;
use App\Exceptions\DatabaseQueryException;
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

    private $runner;

    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        parent::__construct($dbsource, $fieldsource);
        $this->connectionString = "DRIVER={IBM DB2 ODBC DRIVER};DATABASE={$this->dbSource->db_name};" .
            "HOSTNAME={$this->dbSource->server};PORT={$this->dbSource->port};PROTOCOL=TCPIP;UID={$this->dbSource->username};PWD={$this->dbSource->password};";

        $this->runner = resolve(DB2QueryRunner::class);
        $this->connect();
    }

    public function connect()
    {
        $this->connection = $this->runner->db2_connect($this->connectionString, '', '');

        if ($this->connection === false) {
            Log::error($this->formatErrors($this->runner->db2_conn_errormsg()));
            throw new DatabaseConnectionException('There was a problem in connecting to ' . $this->dbSource->dataSource->name);
        }
        $this->isConnected = TRUE;
    }


    public function executeQuery()
    {
        $resource = $this->runner->db2_prepare($this->connection, $this->fieldsource->query);

        if ($resource === false || $this->runner->db2_execute($resource) === false) {
            Log::error($this->formatErrors($this->runner->db2_stmt_errormsg($resource)));
            throw new DatabaseQueryException('There was an error preparing the database query.');
        }

        $resultSet = $this->runner->db2_fetch_assoc($resource);

        $this->close();

        return $resultSet;

    }

    public function close()
    {
        $this->runner->db2_close($this->connection);
    }
}
