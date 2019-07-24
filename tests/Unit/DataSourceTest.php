<?php

namespace Tests\Unit;

use App\DatabaseSource;
use App\DataSource;
use App\WebserviceSource;
use Symfony\Component\VarDumper\Cloner\Data;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataSourceTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_can_save_a_database_source()
    {

        $databaseSource = factory(DatabaseSource::class)->create([
            'server' => '127.0.0.1'
        ]);

        $dbSource = factory(DataSource::class)->create([
            'name' => 'internal_data_warehouse',
            'source_id' => $databaseSource->id,
            'source_type' => DatabaseSource::class
        ]);

        $this->assertEquals('internal_data_warehouse', $dbSource->name);

        $this->assertEquals('127.0.0.1', $dbSource->source->server);

    }

    /** @test */
    public function it_can_save_a_webservice_source()
    {
        $webserviceSource = factory(WebserviceSource::class)->create([
            'url' => 'http://url.to.api/'
        ]);

        $dbSource = factory(DataSource::class)->create([
            'name' => 'external_web_service_ABC',
            'source_id' => $webserviceSource->id,
            'source_type' => WebserviceSource::class
        ]);

        $this->assertEquals('external_web_service_ABC', $dbSource->name);

        $this->assertEquals('http://url.to.api/', $dbSource->source->url);

    }

}
