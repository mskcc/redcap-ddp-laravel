<?php


namespace Tests\Stubs;

class DB2
{
    public static function successfulQuery(){
        return [
            'db2_connect' => true,
            'db2_prepare' => [],
            'db2_execute' => true,
            'db2_stmt_errormsg' => null,
            'db2_close' => true,
            'db2_num_rows' => 1
        ];
    }

}
