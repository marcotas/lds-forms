<?php

use Faker\Generator as Faker;
use App\Models\User;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    return [
        'name'     => $faker->unique()->sentence,
        'link'     => $faker->unique()->url,
        'position' => rand(1, 3),
        'date'     => $faker->boolean() ? $faker->dateTimeBetween('-3 months', '+3 months') : null,
        'user_id'  => function ($topic) use ($faker) {
            return ($topic['date'] !== null && $faker->boolean(5))
                ? (optional(User::all()->shuffle()->first())->id)
                : null;
        },
    ];
});
