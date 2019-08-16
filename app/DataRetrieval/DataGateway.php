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
    public function retrieve($project, $fieldList = [])
    {
        $allMetadata = ProjectMetadata::where('project_id', $project)->get();

        $requestedData = $allMetadata->whereIn('field', $fieldList->pluck('field'));

        $json = collect();

        $requestedData->each(function($fieldMetadata) use ($json) {

            $fieldSource = FieldSource::where('name', $fieldMetadata->dictionary)->get();

            $fieldSource->each(function($field) use ($json, $fieldMetadata) {

                $dataSource = DataSource::with('source')->where('name', $field->data_source)->firstOrFail();

                //Test - what if we have multiple?
                switch(true) {
                    case $dataSource->source instanceof DatabaseSource:

                        $connection = $this->createDatabaseConnection($dataSource->source, $field);

                        $json->add($this->formatResults($fieldMetadata->field, $connection->executeQuery()));

                        break;
                    case $dataSource->source instanceof WebserviceSource:
                        throw new \Exception('Web service queries are not yet implemented.');
                        break;
                    default:
                        return null;
                }

            });

        });

        return $json->toArray();
    }

    protected function formatResults($field, $resultSet)
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
