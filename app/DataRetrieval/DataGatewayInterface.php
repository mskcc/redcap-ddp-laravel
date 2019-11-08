<?php


namespace App\DataRetrieval;


use App\DatabaseSource;
use App\DataRetrieval\Database\DatabaseConnection;
use App\FieldSource;
use App\ProjectMetadata;

interface DataGatewayInterface
{
    public function retrieve(ProjectMetadata $fieldMetadata, $id);
    public function formatResults(ProjectMetadata $projectMetadata, $resultSet);
    public function createDatabaseConnection(DatabaseSource $source, FieldSource $field) : DatabaseConnection;
}
