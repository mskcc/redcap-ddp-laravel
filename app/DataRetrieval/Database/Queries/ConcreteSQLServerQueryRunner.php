<?php

namespace App\DataRetrieval\Database\Queries;

use mysql_xdevapi\Exception;

class ConcreteSQLServerQueryRunner implements SQLServerQueryRunner
{

    public function sqlsrv_query($connection, $query)
    {
        // TODO: Implement sqlsrv_query() method.
        throw new \Exception('Not yet implemented.');
    }

    public function sqlsrv_connect()
    {
        // TODO: Implement sqlsrv_connect() method.
        throw new \Exception('Not yet implemented.');
    }

    public function sqlsrv_close()
    {
        // TODO: Implement sqlsrv_close() method.
        throw new \Exception('Not yet implemented.');
    }

    public function sqlsrv_errors()
    {
        // TODO: Implement sqlsrv_errors() method.
        throw new \Exception('Not yet implemented.');
    }

    public function sqlsrv_fetch_array()
    {
        // TODO: Implement sqlsrv_fetch_array() method.
        throw new \Exception('Not yet implemented.');
    }
}
