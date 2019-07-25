<?php

namespace Tests\Unit;

use App\DatabaseSource;
use App\DataRetrieval\DatabaseQuery;
use App\FieldSource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseQueryTest extends TestCase
{
    /** @test */
    function can_execute_a_database_query()
    {
        $databaseSource = factory(DatabaseSource::class)->create();
        $query = new DatabaseQuery($databaseSource);

        $return = $query->execute();

        $this->assertIsArray($return);
        $this->assertArrayHasKey('field', $return);
        $this->assertArrayHasKey('value', $return);
    }
}
