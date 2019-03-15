<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\Searchable;
use App\Traits\Models\BelongsToTeam;

class Speech extends Model
{
    use Searchable, BelongsToTeam;

    protected $fillable = [
        'title',
        'link',
        'duration',
        'order',
        'team_id',
        'speaker_id',
        'date',
        'invited_at',
        'invited_by',
        'approved_at',
        'approved_by',
        'confirmed_at',
        'confirmed_by',
    ];

    protected $dates = [
        'approved_at',
        'invited_at',
        'confirmed_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    protected $with = ['speaker', 'approvedBy', 'invitedBy', 'confirmedBy'];

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function speaker()
    {
        return $this->belongsTo(User::class, 'speaker_id');
    }
}
