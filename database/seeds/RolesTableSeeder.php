<?php

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::create(['name' => Role::ADMIN]);
        $admin->givePermissionTo('*');

        $marco  = User::find(1);
        $marco->assignRole(Role::ADMIN);

        Team::all()->each(function (Team $team) use ($marco) {
            $roleOwner = $team->roles()->create(['name' => Role::OWNER]);
            $roleOwner->givePermissionTo('manage.team', $team);

            $member = $team->roles()->create(['name' => Role::MEMBER]);

            if (!$marco->onTeam($team)) {
                $marco->joinTeam($team);
            }

            if (!$team->owner) {
                $team->owner()->associate($marco);
            }
            $team->owner->joinTeam($team, Role::OWNER);
        });
    }
}
