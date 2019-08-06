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
        $dataSource = factory(DataSource::class)->make([
            'name' => 'internal_data_warehouse'
        ]);

        factory(DatabaseSource::class, 5)->create()->each(function($dbSource) use ($dataSource) {
            $dataSource->source()->associate($dbSource);
            $dataSource->save();
        });

    }
}
