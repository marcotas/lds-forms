<?php

namespace App\Http\Controllers;

use App\Traits\ExecuteActions;
use App\Traits\Paginations;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        ExecuteActions,
        Paginations;

    protected $minPerPage = 1;
    protected $maxPerPage = 100;

    public function currentUser()
    {
        return Auth::user();
    }
}
