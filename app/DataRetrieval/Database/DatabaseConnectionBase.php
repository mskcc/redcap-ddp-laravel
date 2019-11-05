<?php


namespace App\DataRetrieval\Database;

use App\DatabaseSource;
use App\FieldSource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class DatabaseConnectionBase implements DatabaseConnection
{
    /**
     * @var DatabaseSource
     */
    protected $dbSource;

    /**
     * @var FieldSource
     */
    protected $fieldsource;

    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        $this->dbSource = $dbsource;
        $this->fieldsource = $fieldsource;
    }

    public function executeQuery($id)
    {
        try {
            return DB::connection($this->dbSource->dataSource->name)->select($this->fieldsource->getQueryFor($id));
        } catch (\Exception $e)
        {
            Log::error($e->getMessage());
        }

        return false;

    }
}
