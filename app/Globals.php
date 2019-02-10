<?php

namespace App;

use App\Models\Permission;
use App\Models\Role;

class Globals
{
    public static function variables(): array
    {
        return array_sort([
            'user'        => user(),
            'teams'       => optional(user())->teams,
            'team'        => optional(user())->current_team,
            'roles'       => array_sort(Role::ALL),
            'permissions' => array_values(Permission::names()->toArray()),
        ]);
    }
}
