<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use App\WebserviceSource;
use Faker\Generator as Faker;

$factory->define(WebserviceSource::class, function (Faker $faker) {
    return [
        'url' => $faker->url
    ];
});