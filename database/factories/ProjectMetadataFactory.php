<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DatabaseSource;
use App\DatabaseType;
use App\FieldSource;
use App\ProjectMetadata;
use Faker\Generator as Faker;

$factory->define(ProjectMetadata::class, function (Faker $faker) {
    return [
        'field' => $faker->word,
        'project_id' => $faker->numberBetween(1, 5000),
        'label' => $faker->sentence,
        'description' => $faker->paragraph,
        'temporal' => 0
    ];
});

$factory->state(ProjectMetadata::class, 'with_source', function (\Faker\Generator $faker) {
    return [
        'field_source_id' => factory(FieldSource::class)->state('with_source')->create()->id
    ];
});

