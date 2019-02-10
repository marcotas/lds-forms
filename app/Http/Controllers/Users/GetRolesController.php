<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetRolesController extends Controller
{
    public function __invoke()
    {
        abort_if(!auth()->user()->is_admin, Response::HTTP_NOT_FOUND);

        return collect(Role::globals()->get())->map(function ($role) {
            return [
                'value' => $role->name,
                'name'  => title_case($role->label),
            ];
        })->toArray();
    }
}
