<?php


namespace App\DataRetrieval;

use App\DatabaseSource;
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

        $fields = $fieldList->flatten(1)->toArray();

        $requestedData = $allMetadata->whereIn('field', $fields);

        $json = collect();

        $requestedData->each(function($fieldMetadata) use ($json) {

            $fieldSource = FieldSource::where('name', $fieldMetadata->dictionary)->get();

            $fieldSource->each(function($field) use ($json, $fieldMetadata) {

                $dataSource = DataSource::with('source')->where('name', $field->data_source)->firstOrFail();

                //Test - what if we have multiple?
                switch(true) {
                    case $dataSource->source instanceof DatabaseSource:

                        $connection = new DatabaseConnectionFactory($dataSource->source, $field);

                        $json->add($this->formatResult($fieldMetadata->field, $connection->getConnection()->executeQuery()));

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

    protected function formatResult($field, $value)
    {
        return [
            'field' => $field, 'value' => collect($value)->first()
        ];
    }

}
