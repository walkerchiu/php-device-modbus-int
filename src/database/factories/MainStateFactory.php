<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\DeviceModbus\Models\Entities\MainState;
use WalkerChiu\DeviceModbus\Models\Entities\MainStateLang;

$factory->define(MainState::class, function (Faker $faker) {
    return [
        'main_id'    => 1,
        'serial'     => $faker->isbn10,
        'identifier' => $faker->slug,
        'mean'       => $faker->slug
    ];
});

$factory->define(MainStateLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
