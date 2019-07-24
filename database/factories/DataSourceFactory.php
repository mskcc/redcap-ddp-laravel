<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DataSource;
use App\Model;
use Faker\Generator as Faker;

$factory->define(DataSource::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
