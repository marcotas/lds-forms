<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserPersonalInfoUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdatePersonalInfoController extends Controller
{
    public function __invoke(UserPersonalInfoUpdateRequest $request, User $user)
    {
        return DB::transaction(function () use ($request, $user) {
            $user->update($request->validated());

            $this->addMediaToUser('new_photo', 'photo', $user);

            return $user;
        });
    }

    private function addMediaToUser($field, $collection, User $user)
    {
        if (request()->has($field)) {
            $user->addMediaFromRequest($field)->toMediaCollection($collection);
        }
    }
}
