<?php


namespace App\DataRetrieval;

use App\DatabaseSource;
use App\DataRetrieval\Database\DatabaseConnection;
use App\DataRetrieval\Database\DatabaseConnectionFactory;
use App\DataSource;
use App\FieldSource;
use App\ProjectMetadata;
use App\WebserviceSource;
use phpDocumentor\Reflection\Project;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Exceptions\DatabaseQueryException;

class DataGateway implements DataGatewayInterface
{

    /**
     * Retrieve data, given a configured ProjectMetadata entity.
     * @param ProjectMetadata $fieldMetadata
     * @return array|null
     * @throws \Exception
     */
    public function retrieve(ProjectMetadata $fieldMetadata, $id)
    {
        switch(true) {
            case $fieldMetadata->fieldSource->dataSource->source instanceof DatabaseSource:

                $connection = $this->createDatabaseConnection($fieldMetadata->fieldSource->dataSource->source, $fieldMetadata->fieldSource);

                return $this->formatResults($fieldMetadata, $connection->executeQuery($id));

                break;
            case $fieldMetadata->fieldSource->dataSource->source instanceof WebserviceSource:
                throw new \Exception('Web service queries are not yet implemented.');
                break;
            default:
                return null;
        }

    }

    /**
     * Format the results of the data retrieval operation for REDCap
     * @param ProjectMetadata $metadata
     * @param $resultSet
     * @return array
     */
    public function formatResults(ProjectMetadata $metadata, $resultSet)
    {
        if($resultSet == false){
            throw new DatabaseQueryException("There was an issue retrieving results from the database.");
        }

        try{
            $res = collect($resultSet)->map(function($row) use ($metadata) {
                $columnName = $metadata->fieldSource->column;
                $fieldName = $metadata->field;
                $valueMappings = $metadata->fieldSource->valueMappings;
                $fieldSourceValue = $row->$columnName;
                $tmpResults = ['field' => $fieldName, 'value' => $fieldSourceValue];
                
                if(!$valueMappings->isEmpty()){
                    foreach($valueMappings as $mapping){
                        if($mapping->field_source_value == trim($fieldSourceValue)){
                            $tmpResults['value'] = $mapping->redcap_value;
                            break;
                        }
                    }
                }

                if($metadata->temporal) {
                    $anchor_date = $metadata->fieldSource->anchor_date;

                    array_push($tmpResults, ['timestamp' => $row->$anchor_date]);
                }
                
                return $tmpResults;
            });

            return $res->all();
        } catch (\Exception $e)
        {
            Log::error($e->getMessage());
            throw new DatabaseQueryException("There was an issue parsing the query results from the database.");
        }
    }

    /**
     * Create a database connection, given a database source and a field source.
     * @param DatabaseSource $source
     * @param FieldSource $field
     * @return DatabaseConnection
     * @throws \Exception
     */
    public function createDatabaseConnection(DatabaseSource $source, FieldSource $field) : DatabaseConnection
    {
        $factory = new DatabaseConnectionFactory($source, $field);
        return $factory->createConnection();
    }

}
