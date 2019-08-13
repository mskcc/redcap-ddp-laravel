<?php

namespace App\DataRetrieval\Database\Queries;

interface DB2QueryRunner
{
    public function db2_connect(string $database , string $username, string $password, array $options = null);
    public function db2_prepare($connection , string $statement, array $options = null);
    public function db2_execute($stmt, array $parameters = null) : bool;
    public function db2_stmt_errormsg($stmt = null) : string;
    public function db2_close($connection) : bool;
    public function db2_num_rows($stmt) : int;
    public function db2_fetch_assoc($stmt, $row_number = null) : array;
}
