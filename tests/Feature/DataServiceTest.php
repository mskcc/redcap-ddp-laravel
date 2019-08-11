<?php

namespace Tests\Feature;

use App\DatabaseSource;
use App\DataRetrieval\Database\Queries\ConcreteSQLServerQueryRunner;
use App\DataRetrieval\Database\Queries\SQLServerQueryRunner;
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

    /**
     * @var ConcreteSQLServerQueryRunner|\Mockery\MockInterface
     */
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
        $this->queryRunner = \Mockery::mock(ConcreteSQLServerQueryRunner::class);
        $this->app->instance(SQLServerQueryRunner::class, $this->queryRunner);
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

        $this->queryRunner->allows($this->successfulQuery([
            'date_of_birth' => '1950-01-01'
        ]));

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

        $databaseSource = factory(DatabaseSource::class)->state('sqlserver')->create([
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
        $response->assertJsonFragment([
            'field' => 'birth_date',
            'value' => '1950-01-01'
        ]);

    }


    private function successfulQuery($withData = [])
    {
        return [
            'sqlsrv_connect' => true,
            'sqlsrv_query' => [],
            'sqlsrv_fetch_array' => $withData,
            'sqlsrv_close' => true,
            'sqlsrv_errors' => null,
            'sqlsrv_free_stmt' => true
        ];
    }

}
