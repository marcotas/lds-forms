<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function impersonate(Request $request, User $user)
    {
        $request->session()->flush();

        $request->session()->put('admin:impersonator', user()->id);
        $request->session()->put('admin:impersonator:previous_url', url()->previous());

        Auth::login($user);

        return redirect(route('home'));
    }

    public function stopImpersonating(Request $request)
    {
        $currentId = Auth::id();

        if (!session()->has('admin:impersonator')) {
            Auth::logout();

            return redirect('/');
        }

        $userId      = session()->pull('admin:impersonator');
        $previousUrl = session()->pull('admin:impersonator:previous_url');

        session()->flush();

        Auth::login(User::findOrFail($userId));

        return redirect($previousUrl);
    }
}
