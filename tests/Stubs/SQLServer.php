<?php


namespace Tests\Stubs;

class SQLServer
{
    public static function successfulQuery(){
        return [
            'sqlsrv_connect' => true,
            'sqlsrv_query' => [],
            'sqlsrv_close' => true,
            'sqlsrv_errors' => null,
            'sqlsrv_free_stmt' => true
        ];
    }

}
