<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function data_service_endpoint_accepts_POST_with_valid_params()
    {
        $response = $this->postJson('/api/data', [
            'project_id' => '12345',
            'id' => 'mrn',
            'fields' => [
                ['field' => 'mrn'],
                ['field' => 'dob']
            ]
        ]);

        $response->assertStatus(200);
    }

}
