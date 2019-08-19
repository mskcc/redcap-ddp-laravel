<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DataSource;
use App\FieldSource;
use App\Model;
use App\ProjectMetadata;
use Faker\Generator as Faker;

$factory->define(DataSource::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});



$factory->state(DataSource::class, 'sqlserver', function (\Faker\Generator $faker) {
    return [
        'source_id' => factory(FieldSource::class)->state('with_source')->create()->id
    ];
});
