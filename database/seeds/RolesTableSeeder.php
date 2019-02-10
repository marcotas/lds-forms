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

        $marco  = User::find(2);
        $victor = User::find(3);

        User::find(1)->assignRole(Role::ADMIN);
        $marco->assignRole(Role::ADMIN);
        $victor->assignRole(Role::ADMIN);

        Team::all()->each(function (Team $team) use ($marco, $victor) {
            $roleOwner = $team->roles()->create(['name' => Role::OWNER]);
            $roleOwner->givePermissionTo('manage.team.members', $team);
            $roleOwner->givePermissionTo('manage.team.settings', $team);
            $roleOwner->givePermissionTo('manage.team.billings', $team);
            $roleOwner->givePermissionTo('manage.clients', $team);

            $cashier = $team->roles()->create(['name' => Role::CASHIER]);
            // put the cashier permissions here

            $manager = $team->roles()->create(['name' => Role::MANAGER]);
            // put the manager permissions here

            $professional = $team->roles()->create(['name' => Role::PROFESSIONAL]);
            // put the manager permissions here

            $receptionist = $team->roles()->create(['name' => Role::RECEPTIONIST]);
            // put the manager permissions here

            if (!$marco->onTeam($team)) {
                $marco->joinTeam($team);
            }

            if (!$victor->onTeam($team)) {
                $victor->joinTeam($team);
            }

            if (!$team->owner) {
                $team->owner()->associate(create(User::class));
            }
            $team->owner->joinTeam($team, Role::OWNER);

            User::find(4)->joinTeam($team);
        });
    }
}
