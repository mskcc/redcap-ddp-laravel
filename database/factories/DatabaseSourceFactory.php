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
        'db_type' => $faker->randomElement(DatabaseType::all(['id'])),
        'port' => $faker->numberBetween(1000, 9000)
    ];
});

$factory->state(DatabaseSource::class, 'sqlserver', function (\Faker\Generator $faker) {
    return [
        'db_type' => DatabaseType::where('name', 'sqlserver')->first()
    ];
});

$factory->state(DatabaseSource::class, 'db2', function (\Faker\Generator $faker) {
    return [
        'db_type' => DatabaseType::where('name', 'db2')->first()
    ];
});
