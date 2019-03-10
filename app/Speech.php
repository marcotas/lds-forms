<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speech extends Model
{
    protected $fillable = [
        'name',
        'link',
        'duration',
        'order',
        'speaker_id',
        'date',
        'invited_at',
        'invited_by',
        'approved_at',
        'approved_by',
        'confirmed_at',
        'confirmed_by',
    ];
}
