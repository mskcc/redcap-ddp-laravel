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
     * @var DataGateway
     */
    private $datagateway;

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
    public function data_can_be_retrieved_from_sql_server_for_a_specific_field()
    {
        $this->withoutExceptionHandling();
        $this->queryRunner = \Mockery::mock(ConcreteSQLServerQueryRunner::class);
        $this->app->instance(SQLServerQueryRunner::class, $this->queryRunner);

        $projectId = 12345;
        ProjectSourceFactory::new()->withMetadata([
            'project_id' => $projectId,
            'field' => 'birth_date',
            'label' => 'Subject Birth Date',
            'dictionary' => 'dob'
        ])->backedByDatabase('sqlserver');

        $this->queryRunner->allows(SQLServer::successfulQuery());

        $this->queryRunner->shouldReceive('sqlsrv_fetch_array')
            ->andReturn(
                ['bday' => '1950-01-01'],
                null
            );

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
    public function data_can_be_retrieved_from_db2_for_a_specific_field()
    {
        $this->withoutExceptionHandling();
        $this->queryRunner = \Mockery::mock(ConcreteDB2QueryRunner::class);
        $this->app->instance(DB2QueryRunner::class, $this->queryRunner);

        $projectId = 12345;
        ProjectSourceFactory::new()->withMetadata([
            'project_id' => $projectId,
            'field' => 'birth_date',
            'label' => 'Subject Birth Date',
            'dictionary' => 'dob'
        ])->backedByDatabase('db2');

        $this->queryRunner->allows(DB2::successfulQuery());

        $this->queryRunner->shouldReceive('db2_fetch_assoc')
            ->andReturn(
                ['bday' => '1950-01-01'],
                null
            );

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

}
