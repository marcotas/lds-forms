<?php

namespace App\Filters;

use App\Filters\Filters;

class RecipeFilters extends Filters
{
    public $filters = [
        'search'
    ];

    public function search($text)
    {
        return $this->builder
            ->where('name', 'ilike', "%{$text}%")
            ->orWhere('description', 'ilike', "%{$text}%");
    }
}
