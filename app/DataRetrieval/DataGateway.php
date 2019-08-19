<?php


namespace App\DataRetrieval;

use App\DatabaseSource;
use App\DataRetrieval\Database\DatabaseConnection;
use App\DataRetrieval\Database\DatabaseConnectionFactory;
use App\DataSource;
use App\FieldSource;
use App\ProjectMetadata;
use App\WebserviceSource;

class DataGateway implements DataGatewayInterface
{
    public function retrieve($fieldMetadata)
    {
        switch(true) {
            case $fieldMetadata->fieldSource->dataSource->source instanceof DatabaseSource:

                $connection = $this->createDatabaseConnection($fieldMetadata->fieldSource->dataSource->source, $fieldMetadata->name);

                return $this->formatResults($fieldMetadata->field, $connection->executeQuery());

                break;
            case $fieldMetadata->fieldSource->dataSource->source instanceof WebserviceSource:
                throw new \Exception('Web service queries are not yet implemented.');
                break;
            default:
                return null;
        }

    }

    public function formatResults($field, $resultSet)
    {
        $return = collect($resultSet)->map(function($rows) use ($field) {
            return collect($rows)->map(function($value) use ($field) {
                return [
                    'field' => $field, 'value' => $value
                ];
            })->values();
        })->toArray();
        return $return;
    }

    public function createDatabaseConnection($source, $field) : DatabaseConnection
    {
        $factory = new DatabaseConnectionFactory($source, $field);
        return $factory->createConnection();
    }

}
