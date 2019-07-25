<?php


namespace App\DataRetrieval;

use App\DatabaseSource;

class DatabaseQuery
{
    /**
     * @var DatabaseSource
     */
    private $source;

    public function __construct(DatabaseSource $source)
    {

        $this->source = $source;
    }

    public function execute()
    {
        switch($this->source->dbType->name)
        {
            case 'mysql':
                //TODO: Query MySQL database
            case 'postgresql':
                //TODO: Query PostgreSQL database
            case 'mssql':
                //TODO: Query SQL Server database
            case 'db2':
                //TODO: Query DB2 database
                return $this->formatResult('change', 'later');
            default:
                return $this->formatResult('change', 'later');
        }
    }

    protected function formatResult($field, $value)
    {
        return [
            'field' => $field, 'value' => $value
        ];
    }

}