<?php

use App\Models\Team;
use Faker\Generator as Faker;

$factory->define(App\Models\Client::class, function (Faker $faker) {
    return [
        'name'     => $faker->name,
        'email'    => $faker->email,
        'phone'    => $faker->phoneNumber,
        'birthday' => $faker->dateTimeInInterval('-50 years', '-10 years'),
        'team_id'  => function () {
            return create(Team::class);
        }
    ];
});
