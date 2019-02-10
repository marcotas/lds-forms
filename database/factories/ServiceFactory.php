<?php

use App\Models\Team;
use Faker\Generator as Faker;

$factory->define(App\Models\Service::class, function (Faker $faker) {
    return [
        'name'        => $faker->words(2, true),
        'price'       => $faker->numberBetween(10, 900),
        'description' => $faker->text,
        'commission'  => rand(10, 90),
        'team_id'     => function () {
            return create(Team::class)->id;
        }
    ];
});
