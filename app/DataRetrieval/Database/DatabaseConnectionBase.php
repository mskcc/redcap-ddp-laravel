<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\FieldSource;

abstract class DatabaseConnectionBase implements DatabaseConnection
{
    /**
     * @var DatabaseSource
     */
    protected $dbSource;

    /**
     * @var FieldSource
     */
    protected $fieldsource;

    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        $this->dbSource = $dbsource;
        $this->fieldsource = $fieldsource;
    }
}