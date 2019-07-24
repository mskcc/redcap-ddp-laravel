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

        $dataSource = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        $dataSource->source()->associate($databaseSource);
        $dataSource->save();

        $this->assertEquals('internal_data_warehouse', $dataSource->name);

        $this->assertEquals('127.0.0.1', $dataSource->source->server);

    }

    /** @test */
    public function it_can_save_a_webservice_source()
    {
        $webserviceSource = factory(WebserviceSource::class)->create([
            'url' => 'http://url.to.api/'
        ]);

        $dataSource = factory(DataSource::class)->make([
            'name' => 'external_web_service_ABC'
        ]);

        $dataSource->source()->associate($webserviceSource);
        $dataSource->save();

        $this->assertEquals('external_web_service_ABC', $dataSource->name);

        $this->assertEquals('http://url.to.api/', $dataSource->source->url);

    }

}
