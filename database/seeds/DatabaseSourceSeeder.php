<?php

use App\DatabaseSource;
use App\DataSource;
use App\FieldSource;
use Illuminate\Database\Seeder;

class DatabaseSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataSourceA = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        factory(DatabaseSource::class, 5)->create()->each(function($dbSource) use ($dataSourceA) {
            $dataSourceA->source()->associate($dbSource);
            $dataSourceA->save();
        });

        $dataSourceB = factory(DataSource::class)->make([
            'name' => 'secondary_db'
        ]);

        factory(DatabaseSource::class, 5)->create()->each(function($dbSource) use ($dataSourceB) {
            $dataSourceB->source()->associate($dbSource);
            $dataSourceB->save();
        });

    }
}
