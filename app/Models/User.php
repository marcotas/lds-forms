<?php

namespace App\Models;

use App\Traits\Models\HasTeams;
use App\Traits\Models\Searchable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Auth\MustVerifyEmail as VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasMediaTrait, Searchable, Notifiable, HasTeams, HasRolesAndAbilities, VerifyEmail;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'current_team_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $searchableFields = [
        'name',
        'email',
    ];

    protected $appends = ['is_admin', 'permissions', 'photo_url', 'is_user'];

    public function setPasswordAttribute($password)
    {
        if ($password && !is_bool($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function getIsUserAttribute()
    {
        return $this->password !== null;
    }

    public function getIsAdminAttribute()
    {
        return $this->isAn('admin');
    }

    public function getPermissionsAttribute()
    {
        return $this->abilities;
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 512, 512)
            ->nonQueued()
            ->performOnCollections('photo');
    }

    public function getPhotoAttribute()
    {
        if (!$this->hasMedia('photo')) {
            return null;
        }

        return (object) [
            'original' => $this->getFirstMedia('photo')->getFullUrl(),
            'thumb'    => $this->getFirstMedia('photo')->getFullUrl('thumb')
        ];
    }

    public function getPhotoUrlAttribute()
    {
        return optional($this->photo)->thumb;
    }
}
