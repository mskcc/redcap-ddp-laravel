<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\FieldSource;
use mysql_xdevapi\Exception;

class DatabaseConnectionFactory
{
    /**
     * @var DatabaseSource
     */
    private $dbSource;

    /**
     * @var FieldSource
     */
    private $fieldsource;

    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        $this->dbSource = $dbsource;
        $this->fieldsource = $fieldsource;
    }

    public function execute()
    {
        $connection = null;
        switch($this->dbSource->dbType->name)
        {
            case 'mysql':
                $connection = new MySQLConnection($this->dbSource, $this->fieldsource);
                break;
            case 'postgresql':
                $connection = new PostgreSQLConnection($this->dbSource, $this->fieldsource);
                break;
            case 'mssql':
                $connection = new SQLServerConnection($this->dbSource, $this->fieldsource);
                break;
            case 'db2':
                $connection = new DB2Connection($this->dbSource, $this->fieldsource);
                break;
            default:
                throw new \Exception("database connection type `{$this->dbSource->dbType->name}` not supported.");
        }

        return $connection->execute();
    }

    protected function formatResult($field, $value)
    {
        return [
            'field' => $field, 'value' => $value
        ];
    }

}