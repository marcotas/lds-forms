<?php

namespace App\Models;

use App\Traits\Models\HasRoleAndPermissions;
use App\Traits\Models\HasTeams;
use App\Traits\Models\Searchable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasMediaTrait, Searchable, Notifiable, HasTeams, HasRoleAndPermissions;

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

    protected $appends = ['is_admin', 'role', 'permissions', 'photo_url'];

    public function setPasswordAttribute($password)
    {
        if ($password && !is_bool($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function setRoleAttribute($role)
    {
        if ($this->id) {
            $this->assignRole($role, $this->current_team);
        }
    }

    public function getIsAdminAttribute()
    {
        return $this->isAn(Role::ADMIN);
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

    public function setRoleOnTeam($role, $team): self
    {
        $this->assignRole($role, $team);
        $this->refresh();

        return $this;
    }
}
