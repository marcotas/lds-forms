<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, HasApiTokens, HasMediaTrait, Filterable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['avatar'];

    public function recipes() : HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\Models\Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 512, 512)
            ->nonOptimized()
            ->nonQueued()
            ->performOnCollections('avatar');
    }

    public function getAvatarAttribute()
    {
        return optional($this->getFirstMedia('avatar'))->getFullUrl();
    }
}
