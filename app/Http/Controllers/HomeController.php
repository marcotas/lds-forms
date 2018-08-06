<?php

namespace App\Http\Controllers;

use App\Http\Resources\MinuteResource;
use App\Models\Minute;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->to(route('minutes.index'));
    }
}
