<?php

namespace App\Filters;

use Carbon\Carbon;

class TopicFilters extends Filters
{
    public $filters = [
        'search'
    ];

    public function search($text)
    {
        return $this->builder->where('name', 'ilike', "%{$text}%");
    }
}
