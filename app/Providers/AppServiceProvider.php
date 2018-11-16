<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Relation::morphMap([
            'user' => User::class,
        ]);
    }

    public function register()
    {
        //
    }
}
