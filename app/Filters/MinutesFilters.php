<?php

namespace App\Filters;

use Carbon\Carbon;

class MinutesFilters extends Filters
{
    public $filters = [
        'search'
    ];

    public function search($date)
    {
        try {
            $queryDate = new Carbon($date);

            return $this->builder->whereDate('date', $queryDate);
        } catch (\Exception $e) {
            return $this->builder;
        }
    }
}
