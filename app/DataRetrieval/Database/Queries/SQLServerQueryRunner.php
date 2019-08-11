<?php

namespace App\DataRetrieval\Database\Queries;

interface SQLServerQueryRunner
{
    public function sqlsrv_query($conn, $tsql, $params = [], $options = []);
    public function sqlsrv_connect($server_name, $connection_info = []);
    public function sqlsrv_close($conn);
    public function sqlsrv_errors($errorsAndOrWarnings = SQLSRV_ERR_ALL);
    public function sqlsrv_fetch_array($stmt, $fetch_type = null, $row=null, $offset=null);
    public function sqlsrv_free_stmt($stmt) : bool;
}
