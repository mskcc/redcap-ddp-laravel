<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DataSource;
use App\FieldSource;
use App\Model;
use App\ProjectMetadata;
use Faker\Generator as Faker;

$factory->define(FieldSource::class, function (Faker $faker) {

    $field = $faker->word;
    $col = strtoupper($field);

    return [
        'name' => $field,
        'column' => $col,
        'query' => @"SELECT {$field} AS {$col} FROM PATIENT",
        'temporal' => false
    ];
});


$factory->state(FieldSource::class, 'temporal', function (\Faker\Generator $faker) {
    return [
        'temporal' => true,
        'anchor_date' => 'CREATED_AT'
    ];
});
