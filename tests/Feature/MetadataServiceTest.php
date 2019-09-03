<?php

namespace Tests\Feature;

use App\Database\Factories\DataSourceFactory;
use App\FieldSource;
use App\ProjectMetadata;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MetadataServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function metadata_service_endpoint_accepts_POST_with_valid_params()
    {
        $response = $this->post('/api/metadata', [
            'project_id' => '12345',
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }


    /** @test */
    public function metadata_service_endpoint_returns_422_if_required_params_are_missing()
    {
        $response = $this->post('/api/metadata');

        $response->assertStatus(422);
    }

    /** @test */
    public function metadata_service_endpoint_returns_mapping_fields_from_a_project_config()
    {

        $dataSrc = DataSourceFactory::database('sqlserver');

        $fieldSrc = factory(FieldSource::class)->create([
            'name' => 'dob',
            'data_source_id' => $dataSrc->id
        ]);

        factory(ProjectMetadata::class)->create([
            'project_id' => 12345,
            'field' => 'mrn',
            'field_source_id' => $fieldSrc->id
        ]);

        factory(ProjectMetadata::class)->create([
            'project_id' => 12345,
            'field' => 'birth_date',
            'field_source_id' => $fieldSrc->id
        ]);

        //Act, simulates post from REDCap
        $response = $this->withHeaders([
            'content-type' => 'application/x-www-form-urlencoded'
        ])->post('/api/metadata', [
            'project_id' => '12345',
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'field' => 'mrn'
        ]);
        $response->assertJsonFragment([
            'field' => 'birth_date'
        ]);

    }

}
