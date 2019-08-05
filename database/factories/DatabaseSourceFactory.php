<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DatabaseSource;
use App\DatabaseType;
use App\Model;
use Faker\Generator as Faker;

$factory->define(DatabaseSource::class, function (Faker $faker) {
    return [
        'server' => $faker->domainName,
        'username' => $faker->userName,
        'password' => bcrypt($faker->password),
        'db_name' => $faker->word,
        'db_schema' => $faker->word,
        'db_type' => factory(DatabaseType::class)->create(),
        'port' => $faker->numberBetween(1000, 9000)
    ];
});

$factory->state(DatabaseSource::class, 'sqlserver', function (\Faker\Generator $faker) {
    return [
        'db_type' => factory(DatabaseType::class)->create(['name' => 'sqlserver']),
    ];
});
