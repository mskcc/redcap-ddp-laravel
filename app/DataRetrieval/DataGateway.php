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
    public function retrieve($field, $fieldMetadata)
    {
        $dataSource = DataSource::with('source')->where('name', $field->data_source)->firstOrFail();

        switch(true) {
            case $dataSource->source instanceof DatabaseSource:

                $connection = $this->createDatabaseConnection($dataSource->source, $field);

                return $this->formatResults($fieldMetadata->field, $connection->executeQuery());

                break;
            case $dataSource->source instanceof WebserviceSource:
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
