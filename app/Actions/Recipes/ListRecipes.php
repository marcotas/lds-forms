<?php

namespace App\Actions\Recipes;

use App\Actions\Action;
use App\Filters\RecipeFilters;
use App\Traits\Paginations;
use Illuminate\Http\Request;

class ListRecipes extends Action
{
    use Paginations;

    public $validations = [
        'request' => 'required|object:' . Request::class,
        'filters' => 'required|object:' . RecipeFilters::class,
    ];

    public function execute()
    {
        $user = $this->request->user();
        return $this->request
            ->user()->recipes()
            ->filters($this->filters)
            ->paginate(
                $this->perPage($this->request)
            );
    }
}
