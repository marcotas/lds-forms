<?php

namespace App\Models;

use App\Traits\Models\BelongsToTeam;
use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use Searchable, BelongsToTeam, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birthday',
    ];

    protected $searchableFields = [
        'name',
        'email',
        'phone',
        'birthday',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'birthday',
    ];
}
