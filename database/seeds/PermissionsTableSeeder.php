<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        User::whereIn('id', range(1, 3))->each(function (User $user) {
            $user->assignRole('admin');
        });

        Role::all()->each(function (Role $role) {
            if ($role->name === Role::ADMIN) {
                $role->givePermissionTo('*');
            }

            if ($role->name === 'owner') {
                $role->givePermissionTo('manage.teams');
            }
        });
    }
}
