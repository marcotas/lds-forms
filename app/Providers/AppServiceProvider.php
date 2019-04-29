<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\Site;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Relation::morphMap([
            User::class,
            Role::class,
        ]);

        Blade::if('role', function (...$roles) {
            return user() && user()->isA(...$roles);
        });

        $this->app->singleton('site', function () {
            return new Site();
        });
    }

    public function register()
    {
        //
    }
}
