<?php

use App\Factories\ProjectSourceFactory;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class ProjectMetadataSeeder extends Seeder
{

    /**
     * @var Faker
     */
    private $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\FieldSource::all()->each(function($fieldSrc) {
            factory(\App\ProjectMetadata::class)->create([
                'field_source_id' => $fieldSrc->id
            ]);
        });

    }
}
