<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Topic extends Model
{
    use Filterable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'date'       => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function speaker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeWithoutDate(Builder $builder)
    {
        return $builder->whereNull('date');
    }

    public function scopeFuture(Builder $builder)
    {
        return $builder->whereDate('date', '>', now())
            ->orWhere('date', null);
    }
}
