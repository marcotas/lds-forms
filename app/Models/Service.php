<?php

namespace App\Models;

use App\Traits\Models\BelongsToTeam;
use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes, BelongsToTeam, Searchable;

    protected $fillable = [
        'name',
        'price',
        'description',
        'commission',
    ];

    protected $searchableFields = [
        'name',
        'description',
        'price',
        'commission',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $casts = [
        'price'      => 'float',
        'commission' => 'integer',
    ];

    public function scopeOfTeam(Builder $builder, Team $team)
    {
        return $builder->whereTeamId($team->id);
    }
}
