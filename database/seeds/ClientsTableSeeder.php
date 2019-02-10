<?php

use App\Models\Client;
use App\Models\Team;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    public function run()
    {
        Team::each(function (Team $team) {
            create(Client::class, ['team_id' => $team], rand(5, 25));
        });
    }
}
