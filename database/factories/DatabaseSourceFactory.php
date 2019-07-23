<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DatabaseSource;
use App\Model;
use Faker\Generator as Faker;

$factory->define(DatabaseSource::class, function (Faker $faker) {
    return [
        'type' => 'database',
        'name' => $faker->word,
        'properties' => json_encode([
            'server' => $faker->domainName,
            'username' => $faker->userName,
            'password' => bcrypt($faker->password)
        ])
    ];
});
