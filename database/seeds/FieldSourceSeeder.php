<?php

use Illuminate\Database\Seeder;

class FieldSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\DataSource::all()->each(function($dataSrc) {
            factory(\App\FieldSource::class, 10)->create([
                'data_source_id' => $dataSrc->id
            ]);
        });



    }
}
