<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Speech::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'link'  => $faker->url,
        'date'  => $faker->date,
    ];
});
