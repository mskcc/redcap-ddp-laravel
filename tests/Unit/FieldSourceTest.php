<?php

namespace Tests\Unit;

use App\FieldSource;
use Tests\TestCase;

class FieldSourceTest extends TestCase
{
    /** @test */
    public function it_can_substitute_an_integer_identifier_in_a_query()
    {
        $fieldSource = factory(FieldSource::class)->make([
            'query' => "SELECT gender from PATIENT where id = id"
        ]);

        $this->assertEquals(
            "SELECT gender from PATIENT where id = 1",
            $fieldSource->getQueryFor(1)
        );

    }

    /** @test */
    public function it_can_substitute_a_string_identifier_in_a_query()
    {
        $fieldSource = factory(FieldSource::class)->make([
            'query' => "SELECT gender from PATIENT where id = sid"
        ]);

        $this->assertEquals(
            "SELECT gender from PATIENT where id = '12345678'",
            $fieldSource->getQueryFor("12345678")
        );
    }


}
