<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UpdatePermissionsRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UpdatePermissionsController extends Controller
{
    public function __invoke(UpdatePermissionsRequest $request, User $user): User
    {
        return $user->syncPermissions($request->permissions);
    }
}
