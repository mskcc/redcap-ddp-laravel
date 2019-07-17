<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\ProjectMetadata::class, function (Faker $faker) {
    return [
        'field' => $faker->word,
        'project_id' => $faker->numberBetween(1, 5000),
        'label' => $faker->sentence,
        'description' => $faker->paragraph,
        'temporal' => 0
    ];
});
