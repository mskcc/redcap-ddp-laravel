<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DataSource;
use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\FieldSource::class, function (Faker $faker) {

    $field = $faker->word;
    return [
        'name' => $field,
        'query' => @"SELECT {$field} FROM PATIENT",
        'data_source' => 'internal_data_warehouse',
        'temporal' => false
    ];
});
