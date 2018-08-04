<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Minute extends Model
{
    protected $fillable = [
        'date',
        'presided_by',
        'directed_by',
        'receptionist',
        'conductor',
        'pianist',
        'attendance',
        'ward',
        'stake',
        'welcome',
        'announcement',
        'first_hymn',
        'last_hymn',
        'sacrament_hymn',
        'intermediate_hymn',
        'first_prayer',
        'last_prayer',
        'callings',
        'confirmations',
        'baby_blessings',
        'ordinances',
        'comments',
        'first_speaker',
        'second_speaker',
        'third_speaker',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = isset($value) ? new Carbon($value) : null;
    }
}
