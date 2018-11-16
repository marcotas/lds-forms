<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecipesController extends Controller
{
    public function index(Request $request)
    {
        $recipes = collect();

        return view('recipes.index', compact('recipes'));
    }
}
