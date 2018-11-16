<?php

namespace App\Filters;

use Carbon\Carbon;

class UsersFilters extends Filters
{
    public $filters = [
        'search'
    ];

    public function search($text)
    {
        return $this->builder->where('name', 'ilike', "%{$text}%")
            ->orWhere('email', 'ilike', "%{$text}%");
    }
}
