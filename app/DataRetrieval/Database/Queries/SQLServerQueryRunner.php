<?php

namespace App\DataRetrieval\Database\Queries;

interface SQLServerQueryRunner
{
    /**
     * @param $connection
     * @param $query
     * @return resource|bool
     */
    public function sqlsrv_query($connection, $query);
    public function sqlsrv_connect();
    public function sqlsrv_close();
    public function sqlsrv_errors();
    public function sqlsrv_fetch_array();
}
