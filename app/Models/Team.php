<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use Searchable;

    protected $fillable = [
        'name',
        'owner_id',
        'trial_ends_at',
    ];

    protected $searchableFields = [
        'name',
    ];

    protected $dates = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_teams');
    }

    public function services()
    {
        return $this->hasMany(Service::class)->orderBy('name');
    }

    public function clients()
    {
        return $this->hasMany(Client::class)->orderBy('name');
    }

    public function roles()
    {
        return $this->hasMany(Role::class)->orderBy('name');
    }

    public function getPhotoUrlAttribute($value)
    {
        return empty($value)
                ? 'https://www.gravatar.com/avatar/' . md5($this->name . '@easyservice.com') . '.jpg?s=200&d=identicon'
                : url($value);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
