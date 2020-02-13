<?php

namespace Tests\Unit;

use App\FieldSource;
use Tests\TestCase;

class FieldSourceTest extends TestCase
{
    /** @test */
    public function it_can_return_a_sql_query()
    {
        $fieldSource = factory(FieldSource::class)->make([
            'query' => "SELECT gender from PATIENT where id = ?"
        ]);

        $this->assertEquals(
            "SELECT gender from PATIENT where id = ?",
            $fieldSource->getQuery()
        );

    }


}
