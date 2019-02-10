<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Permission::class, function (Faker $faker) {
    return [
        'name'        => $faker->words(2, true),
        'description' => $faker->words(8, true),
    ];
});
