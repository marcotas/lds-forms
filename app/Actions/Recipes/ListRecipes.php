<?php

namespace App\Actions\Recipes;

use App\Actions\Action;
use App\Filters\RecipeFilters;
use App\Traits\Paginations;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

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

        return QueryBuilder::for($user->recipes()->getQuery())
            ->allowedSorts('id', 'name')
            ->allowedFilters(Filter::exact('status'))
            ->filters($this->filters)
            ->paginate($this->perPage($this->request));
    }
}
