<?php


namespace App\DataRetrieval;


use App\DataRetrieval\Database\DatabaseConnection;

interface DataGatewayInterface
{
    public function retrieve($fieldMetadata);
    public function formatResults($field, $resultSet);
    public function createDatabaseConnection($source, $field) : DatabaseConnection;
}
