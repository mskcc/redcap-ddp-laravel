<?php

namespace Tests\Feature;

use App\Database\Factories\DataSourceFactory;
use App\DatabaseSource;
use App\DataRetrieval\Database\Queries\ConcreteDB2QueryRunner;
use App\DataRetrieval\Database\Queries\ConcreteSQLServerQueryRunner;
use App\DataRetrieval\Database\Queries\DB2QueryRunner;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
use App\DataRetrieval\DataGateway;
use App\DataRetrieval\DataGatewayInterface;
use App\DataSource;
use App\Factories\DatabaseSourceFactory;
use App\Factories\ProjectSourceFactory;
use App\FieldSource;
use App\ProjectMetadata;
use Mockery\MockInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataServiceTest extends TestCase
{
    use RefreshDatabase;

    private $queryRunner;

    /**
     * @var MockInterface
     */
    private $dataGateway;

    public function setUp() : void
    {
        parent::setUp();

        $this->dataGateway = \Mockery::mock(DataGateway::class);
        $this->app->instance(DataGatewayInterface::class, $this->dataGateway);
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
    public function data_can_be_retrieved_from_sql_server_for_a_specific_field()
    {
        $this->withoutExceptionHandling();

        $dataSrc = DataSourceFactory::database('sqlserver');

        $fieldSrc = factory(FieldSource::class)->create([
            'name' => 'dob',
            'data_source_id' => $dataSrc->id
        ]);

        factory(ProjectMetadata::class)->create([
            'project_id' => 12345,
            'field' => 'birth_date',
            'field_source_id' => $fieldSrc->id
        ]);

        $this->dataGateway->shouldReceive('retrieve')
            ->once()
            ->andReturn([
                'field' => 'birth_date',
                'value' => '1950-01-01'
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

        $response->assertJsonFragment([
            'field' => 'birth_date',
            'value' => '1950-01-01'
        ]);

    }

    /** @test */
    public function data_can_be_retrieved_from_sql_server_for_multiple_fields()
    {
        $this->withoutExceptionHandling();

        $projectId = 12345;

        $dataSrc = DataSourceFactory::database('sqlserver');

        $fieldSrcA = factory(FieldSource::class)->create([
            'name' => 'dob',
            'data_source_id' => $dataSrc->id
        ]);

        $fieldSrcB = factory(FieldSource::class)->create([
            'name' => 'sex',
            'data_source_id' => $dataSrc->id
        ]);

        factory(ProjectMetadata::class)->create([
            'project_id' => $projectId,
            'field' => 'birth_date',
            'field_source_id' => $fieldSrcA->id
        ]);

        factory(ProjectMetadata::class)->create([
            'project_id' => $projectId,
            'field' => 'gender',
            'field_source_id' => $fieldSrcB->id
        ]);

        $this->dataGateway->shouldReceive('retrieve')
            ->twice()
            ->andReturn(
                [
                    'field' => 'birth_date',
                    'value' => '1950-01-01'
                ],
                [
                    'field' => 'gender',
                    'value' => 'M'
                ]);

        //Act, simulates post from REDCap
        $response = $this->postJson('/api/data', [
            'project_id' => '12345',
            'id' => '54321',
            'fields' => [
                ['field' => 'birth_date'],
                ['field' => 'gender']
            ]
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'field' => 'birth_date',
            'value' => '1950-01-01'
        ]);

        $response->assertJsonFragment([
            'field' => 'gender',
            'value' => 'M'
        ]);

    }


    /** @test */
    public function data_can_be_retrieved_from_sql_server_for_a_temporal_field()
    {
        $this->withoutExceptionHandling();

        $projectId = 12345;

        $dataSrc = DataSourceFactory::database('sqlserver');

        $fieldSrcA = factory(FieldSource::class)->create([
            'name' => 'glucoseTOL',
            'data_source_id' => $dataSrc->id
        ]);

        factory(ProjectMetadata::class)->create([
            'project_id' => $projectId,
            'field' => 'glucoseTolerance',
            'temporal' => 1,
            'field_source_id' => $fieldSrcA->id
        ]);

        $this->dataGateway->shouldReceive('retrieve')
            ->once()
            ->andReturn([
                ['field' => 'glucoseTolerance', 'value' => '124', 'timestamp' => '2013-09-04 06:55'],
                ['field' => 'glucoseTolerance', 'value' => '105', 'timestamp' => '2013-09-05 08:23'],
                ['field' => 'glucoseTolerance', 'value' => '91', 'timestamp' => '2013-09-05 10:09']
            ]);

        //Act, simulates post from REDCap
        $response = $this->postJson('/api/data', [
            'project_id' => '12345',
            'id' => '54321',
            'fields' => [
                [
                    'field' => 'glucoseTolerance',
                    'timestamp_min' => '2013-09-03 10:51:00',
                    'timestamp_max' => '2013-09-07 10:51:00'
                ],
                [
                    'field' => 'glucoseTolerance',
                    'timestamp_min' => '2013-09-05 00:00:00',
                    'timestamp_max' => '2013-09-09 00:00:00'
                ],
            ]
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'field' => 'glucoseTolerance',
            'value' => '124',
            'timestamp' => '2013-09-04 06:55'
        ]);

    }

    /** @test */
    public function data_can_be_retrieved_from_db2_for_a_specific_field()
    {
        $this->withoutExceptionHandling();

        $dataSrc = DataSourceFactory::database('db2');

        $fieldSrc = factory(FieldSource::class)->create([
            'name' => 'dob',
            'data_source_id' => $dataSrc->id
        ]);

        factory(ProjectMetadata::class)->create([
            'project_id' => 12345,
            'field' => 'birth_date',
            'field_source_id' => $fieldSrc->id
        ]);

        $this->dataGateway->shouldReceive('retrieve')
            ->once()
            ->andReturn(
                [
                    'field' => 'birth_date',
                    'value' => '1950-01-01'
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
        $response->assertJsonFragment([
            'field' => 'birth_date',
            'value' => '1950-01-01'
        ]);
    }


    public function tearDown() :void
    {
        parent::tearDown();
        \Mockery::close();
    }
}
