<?php

use App\Models\Team;
use App\Models\User;

function team(): ? Team
{
    return optional(user())->current_team;
}

function user(): ? User
{
    return auth()->user();
}

function impersonator(): ? User
{
    return User::find(session()->get('admin:impersonator'));
}
