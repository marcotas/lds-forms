<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Minute::class, function (Faker $faker) {
    return [
        'date' => $faker->date(),
    ];
});
