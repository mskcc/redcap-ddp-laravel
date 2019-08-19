<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DataSource;
use App\FieldSource;
use App\Model;
use App\ProjectMetadata;
use Faker\Generator as Faker;

$factory->define(\App\FieldSource::class, function (Faker $faker) {

    $field = $faker->word;
    return [
        'name' => $field,
        'query' => @"SELECT {$field} FROM PATIENT",
        'temporal' => false
    ];
});
