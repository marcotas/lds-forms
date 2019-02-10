<?php

namespace App\Providers;

use App\Models\Team;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RolesAndPermissionsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::if('role', function ($role) {
            return user() && user()->isA($role);
        });
        Blade::if('can', function ($permission, $team = null) {
            $team = is_object($team) ? $team : Team::find($team);
            $team = $team ?? team();

            return user() && user()->can($permission, $team);
        });
    }

    public function register()
    {
        //
    }
}
