<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Relation::morphMap([
            User::class,
            Role::class,
        ]);

        Blade::if('role', function ($role) {
            return user() && user()->isA($role);
        });
    }

    public function register()
    {
        //
    }
}
