<?php

namespace App\DataRetrieval\Database\Queries;

class ConcreteDB2QueryRunner implements DB2QueryRunner
{
    public function db2_prepare($connection, string $statement, array $options = null)
    {
        return db2_prepare($connection, $statement, $options);
    }

    public function db2_execute($stmt, array $parameters = null): bool
    {
        return db2_execute($stmt, $parameters);
    }

    public function db2_stmt_errormsg($stmt = null): string
    {
        return db2_stmt_errormsg($stmt);
    }

    public function db2_close($connection): bool
    {
        return db2_close($connection);
    }

    public function db2_num_rows($stmt): int
    {
        return db2_num_rows($stmt);
    }

    public function db2_fetch_assoc($stmt, $row_number = null): array
    {
        return db2_fetch_assoc($stmt, $row_number);
    }

    public function db2_connect(string $database, string $username, string $password, array $options = null)
    {
        return db2_connect($database, $username, $password, $options);
    }
}
