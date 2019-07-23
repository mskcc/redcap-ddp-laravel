<?php

namespace Tests\Unit;

use App\DatabaseSource;
use App\DataSource;
use Symfony\Component\VarDumper\Cloner\Data;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataSourceTest extends TestCase
{
    /** @test */
    public function testExample()
    {
        $dbSource = factory(DatabaseSource::class)->create();
        $this->assertEquals('database', $dbSource->type);
    }
}
