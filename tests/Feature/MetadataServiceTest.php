<?php

namespace Tests\Feature;

use App\ProjectMetadata;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
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
    public function metadata_service_endpoint_returns_mapping_fields_from_a_project_config()
    {
        factory(ProjectMetadata::class)->create([
            'project_id' => 12345,
            'field' => 'mrn',
            'label' => 'Medical Record Number'
        ]);

        factory(ProjectMetadata::class)->create([
            'project_id' => 12345,
            'field' => 'birth_date',
            'label' => 'Subject Birth Date'
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
