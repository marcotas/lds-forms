<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'label'
    ];

    public static function names()
    {
        return collect(self::all()->map->name->toArray());
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'permissible');
    }

    public function scopeNamed(Builder $builder, $name)
    {
        return $builder->whereRaw('LOWER(name) = ?', strtolower($name));
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name) ?: null;
    }

    public function getLabelAttribute()
    {
        return __('permissions.' . $this->attributes['name']);
    }
}
