<?php

namespace App\DataRetrieval\Database;

interface DatabaseConnection
{
    public function connect();
    public function executeQuery();
    public function close();
}