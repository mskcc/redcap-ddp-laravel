<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DatabaseType;
use App\Model;
use Faker\Generator as Faker;

$factory->define(DatabaseType::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement([
           'mysql', 'mssql', 'postgresql', 'db2'
        ])
    ];
});
