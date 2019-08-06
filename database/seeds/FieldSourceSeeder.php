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
        factory(\App\FieldSource::class, 50)->create();
    }
}
