<?php

namespace App\Http\Controllers\Api;

use App\Actions\Recipes\ListRecipes;
use App\Filters\RecipeFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipesController extends Controller
{
    public function index(Request $request, RecipeFilters $filters)
    {
        return RecipeResource::collection(
            $this->execute(ListRecipes::class, [
                'request' => $request,
                'filters' => $filters
            ])
        );
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Recipe $recipe)
    {
        //
    }

    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
    }
}
