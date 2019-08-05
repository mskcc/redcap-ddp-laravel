<?php

namespace Tests\Unit;

use App\DataRetrieval\DataGateway;
use App\FieldSource;
use App\ProjectMetadata;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataGatewayTest extends TestCase
{

    /** @test */
    function can_get_results_for_a_project()
    {
        $datagateway = new DataGateway();

        $fields = collect([
            'field' => 'dob',
            'field' => 'gender'
        ]);

        $results = $datagateway->retrieve(1, $fields);

        $this->assertIsArray($results);
    }

}
