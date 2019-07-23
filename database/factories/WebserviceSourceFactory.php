<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use App\WebserviceSource;
use Faker\Generator as Faker;

$factory->define(WebserviceSource::class, function (Faker $faker) {
    return [
        'type' => 'webservice',
        'name' => $faker->word,
        'properties' => json_encode([
            'url' => $faker->url
        ])
    ];
});