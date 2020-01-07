<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\DatabaseSource;
use App\DatabaseType;
use App\FieldSource;
use App\ProjectMetadata;
use App\ValueMapping;
use Faker\Generator as Faker;

$factory->define(ValueMapping::class, function (Faker $faker) {
    return [
        'field_source_value' => 'M',
        'redcap_value' => '1'
    ];
});

$factory->state(ValueMapping::class, 'with_source', function (\Faker\Generator $faker) {
    return [
        'field_source_id' => factory(FieldSource::class)->state('with_source')->create()->id
    ];
});
