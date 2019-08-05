<?php

namespace App\DataRetrieval\Database\Queries;

class ConcreteSQLServerQueryRunner implements SQLServerQueryRunner
{
    public function sqlsrv_connect($server_name, $connection_info = [])
    {
        return sqlsrv_connect($server_name, $connection_info);
    }

    public function sqlsrv_close($conn)
    {
        return sqlsrv_close($conn);
    }

    public function sqlsrv_errors($errorsAndOrWarnings = SQLSRV_ERR_ALL)
    {
        return sqlsrv_errors($errorsAndOrWarnings);
    }

    public function sqlsrv_fetch_array($stmt, $fetch_type = null, $row = null, $offset = null)
    {
        return sqlsrv_fetch_array($stmt, $fetch_type, $row, $offset);
    }

    public function sqlsrv_query($conn, $tsql, $params = [], $options = [])
    {
        return sqlsrv_query($conn, $tsql, $params, $options);
    }
}
