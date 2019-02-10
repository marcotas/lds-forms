<?php

use App\Models\Service;
use App\Models\Team;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        Team::all()->each(function ($team) {
            create(Service::class, ['team_id' => $team], rand(3, 10));
        });
    }
}
