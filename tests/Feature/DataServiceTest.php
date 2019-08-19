<?php

namespace Tests\Feature;

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
use Tests\Stubs\DB2;
use Tests\Stubs\SQLServer;
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

        $projectId = 12345;
        ProjectSourceFactory::new()->withMetadata([
            'project_id' => $projectId,
            'field' => 'birth_date',
            'dictionary' => 'dob'
        ])->backedByDatabase('sqlserver');

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
        ProjectSourceFactory::new()->withMetadata([
            'project_id' => $projectId,
            'field' => 'birth_date',
            'dictionary' => 'dob'
        ])->backedByDatabase('sqlserver');

        ProjectSourceFactory::new()->withMetadata([
            'project_id' => $projectId,
            'field' => 'gender',
            'dictionary' => 'dob'
        ])->backedByDatabase('sqlserver');

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

    }

    /** @test */
    public function data_can_be_retrieved_from_db2_for_a_specific_field()
    {
        $this->withoutExceptionHandling();

        $projectId = 12345;
        ProjectSourceFactory::new()->withMetadata([
            'project_id' => $projectId,
            'field' => 'birth_date',
            'label' => 'Subject Birth Date',
            'dictionary' => 'dob'
        ])->backedByDatabase('db2');

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
