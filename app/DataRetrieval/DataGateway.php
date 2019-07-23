<?php


namespace App\DataRetrieval;

use App\DatabaseSource;
use App\FieldSource;
use App\ProjectMetadata;

class DataGateway implements DataGatewayInterface
{
    public function retrieve($project, $fieldList = [])
    {
        $metadata = ProjectMetadata::where('project_id', $project)->get();

        $fields = $fieldList->flatten(1)->toArray();

        $requestedData = $metadata->whereIn('field', $fields);

        $requestedData->each(function($field) {

            $fieldSource = FieldSource::where('name', $field->dictionary)->get();

            //TODO: implement logic for data queries

        });

        return [];

    }

    

}