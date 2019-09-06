<?php

namespace Tests\Feature;

use App\Database\Factories\DataSourceFactory;
use App\FieldSource;
use App\ProjectMetadata;
use Illuminate\Database\SqlServerConnection as CoreSqlServerConnection;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var CoreSqlServerConnection|MockInterface
     */
    private $connection;

    public function setUp() : void
    {
        parent::setUp();

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
            'data_source_id' => $dataSrc->id,
            'column' => 'bday'
        ]);

        $project = factory(ProjectMetadata::class)->make([
            'project_id' => 12345,
            'field' => 'birth_date',
        ]);

        $project->fieldSource()->associate($fieldSrc);
        $project->save();

        $this->connection = \Mockery::mock(CoreSqlServerConnection::class);

        DB::shouldReceive('connection')->andReturn($this->connection);
        $this->connection->shouldReceive('select')
            ->andReturn([
                    (object)['bday' => '1950-01-01', 'created_at' => '2019-01-01']
                ]
            );

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

        $dataSrc = DataSourceFactory::database('sqlserver');

        $fieldSrcA = factory(FieldSource::class)->create([
            'name' => 'dob',
            'data_source_id' => $dataSrc->id,
            'column' => 'bday'
        ]);

        $projectA = factory(ProjectMetadata::class)->make([
            'project_id' => 12345,
            'field' => 'birth_date',
        ]);
        $projectA->fieldSource()->associate($fieldSrcA);
        $projectA->save();

        $fieldSrcB = factory(FieldSource::class)->create([
            'name' => 'sex',
            'data_source_id' => $dataSrc->id,
            'column' => 'SEX'
        ]);

        $projectB = factory(ProjectMetadata::class)->make([
            'project_id' => 12345,
            'field' => 'gender',
        ]);
        $projectB->fieldSource()->associate($fieldSrcB);
        $projectB->save();

        $this->connection = \Mockery::mock(CoreSqlServerConnection::class);

        DB::shouldReceive('connection')->andReturn($this->connection);
        $this->connection->shouldReceive('select')
            ->andReturn(
                [
                    (object)['bday' => '1950-01-01', 'created_at' => '2019-01-01']
                ],
                [
                    (object)['SEX' => 'M', 'created_at' => '2019-01-01']
                ]
            );

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

        $fieldSrcA = factory(FieldSource::class)->state('temporal')->create([
            'name' => 'glucoseTOL',
            'data_source_id' => $dataSrc->id,
            'column' => 'GLUCOSETOL',
            'anchor_date' => 'CREATED_AT'
        ]);

        $project = factory(ProjectMetadata::class)->make([
            'project_id' => $projectId,
            'field' => 'glucoseTolerance',
            'temporal' => 1
        ]);

        $project->fieldSource()->associate($fieldSrcA);
        $project->save();

        $this->connection = \Mockery::mock(CoreSqlServerConnection::class);

        DB::shouldReceive('connection')->andReturn($this->connection);
        $this->connection->shouldReceive('select')
            ->andReturn(
                [
                    (object)['GLUCOSETOL' => '181', 'CREATED_AT' => '2013-09-01 14:32'],
                    (object)['GLUCOSETOL' => '124', 'CREATED_AT' => '2013-09-04 06:55'],
                    (object)['GLUCOSETOL' => '105', 'CREATED_AT' => '2013-09-05 08:23'],
                    (object)['GLUCOSETOL' => '91', 'CREATED_AT' => '2013-09-05 10:09']
                ]
            );


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

    public function tearDown() :void
    {
        parent::tearDown();
        \Mockery::close();
    }
}
