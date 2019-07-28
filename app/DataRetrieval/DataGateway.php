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
        $metadata = ProjectMetadata::where('project_id', $project)->get();

        $fields = $fieldList->flatten(1)->toArray();

        $requestedData = $metadata->whereIn('field', $fields);

        $requestedData->each(function($field) {

            $fieldSource = FieldSource::where('name', $field->dictionary)->get();

            $fieldSource->each(function($field) {

                $dataSource = DataSource::with('source')->where('name', $field->data_source)->firstOrFail();

                //Test - what if we have multiple?
                switch(true) {
                    case $dataSource->source instanceof DatabaseSource:

                        $connection = new DatabaseConnectionFactory($dataSource->source, $field);

                        dd($connection->getConnection());

                        $connection->getConnection()->executeQuery();

                        break;
                    case $dataSource->source instanceof WebserviceSource:
                        throw new \Exception('Web service queries are not yet implemented.');
                        break;
                    default:
                        return null;
                }



            });

        });

        return [];

    }

}