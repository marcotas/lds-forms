<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use Filterable;

    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];
}
