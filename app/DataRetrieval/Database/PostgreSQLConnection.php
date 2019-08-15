<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\FieldSource;

class PostgreSQLConnection extends DatabaseConnectionBase
{
    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        parent::__construct($dbsource, $fieldsource);
    }

    public function connect()
    {
        // TODO: Implement connect() method.
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
