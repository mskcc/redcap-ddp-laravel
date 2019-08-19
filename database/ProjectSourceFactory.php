<?php
namespace App\Factories;

use App\DatabaseSource;
use App\DataSource;
use App\FieldSource;
use App\ProjectMetadata;

class ProjectSourceFactory
{
    public $fieldSource;

    public $metadata;

    public static function new()
    {
        return new static;
    }

    /**
     * @param null $metadata
     * @return mixed
     */
    public function withMetadata($metadata = null)
    {
        $this->metadata = factory(ProjectMetadata::class)->create($metadata);

        $this->fieldSource = factory(FieldSource::class)->create([
            'name' => $this->metadata->dictionary,
            'query' => "SELECT {$this->metadata->field} from dbo.patient where id = id",
            'data_source' => 'internal_data_warehouse'
        ]);

        return $this;
    }

    public function backedByDatabase($dbType = 'sqlserver')
    {
        $dataSource = DataSource::where("name", "=", $this->fieldSource->data_source)->first();

        if($dataSource == null)
        {
            $databaseSource = factory(DatabaseSource::class)->state($dbType)->create([
                'server' => '127.0.0.1'
            ]);

            $dataSource = factory(DataSource::class)->make([
                'name' => $this->fieldSource->data_source
            ]);

            $dataSource->source()->associate($databaseSource);

            $dataSource->save();
        }

        return $this;

    }
}
