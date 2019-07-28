<?php

namespace Tests\Feature;

use App\DatabaseSource;
use App\DataRetrieval\DataGateway;
use App\DataRetrieval\DataGatewayInterface;
use App\DataSource;
use App\FieldSource;
use App\ProjectMetadata;
use Symfony\Component\VarDumper\Cloner\Data;
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
        $this->withoutExceptionHandling();

        //Arrange
        factory(ProjectMetadata::class)->create([
            'project_id' => 12345,
            'field' => 'birth_date',
            'label' => 'Subject Birth Date',
            'dictionary' => 'dob'
        ]);

        factory(FieldSource::class)->create([
        'name' => 'dob',
        'query' => "SELECT date_of_birth from dbo.patient",
        'data_source' => 'internal_data_warehouse'
    ]);

        $databaseSource = factory(DatabaseSource::class)->create([
            'server' => '127.0.0.1'
        ]);

        $dataSource = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        $dataSource->source()->associate($databaseSource);
        $dataSource->save();

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
