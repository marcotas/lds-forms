<?php

use App\Models\Recipe;
use Faker\Generator as Faker;

$factory->define(Recipe::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'description' => $faker->realtext,
    ];
});
