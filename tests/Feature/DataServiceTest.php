<?php

namespace Tests\Feature;

use App\DataRetrieval\DataGateway;
use App\DataRetrieval\DataGatewayInterface;
use App\FieldSource;
use App\ProjectMetadata;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        $this->datagateway = new DataGateway();
        $this->app->instance(DataGatewayInterface::class, $this->datagateway);
    }

        /** @test */
    public function data_service_endpoint_accepts_POST_with_valid_params()
    {

        $response = $this->postJson('/api/data', [
            'project_id' => '12345',
            'id' => '54321',
            'fields' => [
                ['field' => 'gender'],
                ['field' => 'dob']
            ]
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function data_can_be_retrieved_for_a_specific_field()
    {
        //Arrange

        factory(ProjectMetadata::class)->create([
            'project_id' => 12345,
            'field' => 'birth_date',
            'label' => 'Subject Birth Date',
            'dictionary' => 'dob'
        ]);

        factory(FieldSource::class)->create([
            'name' => 'dob'
        ]);

        //Act, simulates post from REDCap
        $response = $this->postJson('/api/data', [
            'project_id' => '12345',
            'id' => '54321',
            'fields' => [
                ['field' => 'birth_date']
            ]
        ]);

        $response->assertStatus(200);
    }




}
