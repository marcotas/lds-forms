<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Paginations
{
    public function perPage(Request $request)
    {
        $maxPerPage = $this->maxPerPage ?? 100;
        $minPerPage = $this->minPerPage ?? 1;
        $perPage    = $request->get('per_page', 15);
        $perPage    = $perPage < $minPerPage ? $minPerPage : $perPage;
        $perPage    = $perPage > $maxPerPage ? $maxPerPage : $perPage;

        return $perPage;
    }
}
