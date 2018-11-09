<?php

namespace App\Models;

use App\Models\Traits\HasSecurePassword;
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
    use Notifiable, HasApiTokens, HasMediaTrait, Filterable, SoftDeletes, HasSecurePassword;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'password',
        'active',
        'remember_token',
    ];
    protected $hidden  = ['password', 'remember_token'];
    protected $appends = ['avatar'];
    protected $casts   = [
        'active'     => 'boolean',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

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
            ->fit(Manipulations::FIT_CROP, 256, 256)
            ->nonOptimized()
            ->nonQueued()
            ->performOnCollections('avatar');
    }

    public function getAvatarAttribute()
    {
        if (!$this->hasMedia('avatar')) {
            return null;
        }

        return [
            'original' => $this->getFirstMedia('avatar')->getFullUrl(),
            'thumb'    => $this->getFirstMedia('avatar')->getFullUrl('thumb'),
        ];
    }
}
