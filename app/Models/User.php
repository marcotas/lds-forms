<?php

namespace App\Models;

use App\Traits\Models\HasTeams;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Silber\Bouncer\Database\HasRolesAndAbilities;
// use Illuminate\Auth\MustVerifyEmail as VerifyEmail;
use MarcoT89\Bullet\Traits\Searchable;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $current_team_id
 * @property string $gender
 * @property mix $photo
 * @property string $photo_url
 * @property bool $is_admin
 * @property bool $is_user
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property \Illuminate\Support\Carbon $phone_verified_at
 */
class User extends Authenticatable implements HasMedia
{
    use HasMediaTrait, Searchable, Notifiable, HasTeams, HasRolesAndAbilities;

    const GENDERS = ['male', 'female'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'current_team_id',
        'gender',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $searchableFields = [
        'name',
        'email',
        'phone',
    ];

    protected $appends = ['is_admin', 'permissions', 'photo_url', 'is_user'];

    protected $dates = [
        'created_at',
        'updated_at',
        'email_verified_at',
        'phone_verified_at',
    ];

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
