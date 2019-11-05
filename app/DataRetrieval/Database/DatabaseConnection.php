<?php

namespace App\DataRetrieval\Database;

use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;

interface DatabaseConnection
{
    public function executeQuery($id);
}
