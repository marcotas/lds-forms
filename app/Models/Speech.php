<?php

namespace App\Models;

use App\Traits\Models\BelongsToTeam;
use App\Traits\Models\Searchable;
use Illuminate\Database\Eloquent\Model;

class Speech extends Model
{
    use Searchable, BelongsToTeam;

    protected $fillable = [
        'title',
        'link',
        'author',
        'image_url',
        'duration',
        'order',
        'team_id',
        'speaker_id',
        'date',
        'invited_at',
        'invited_by',
        'confirmed_at',
        'confirmed_by',
    ];

    protected $dates = [
        'invited_at',
        'confirmed_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    protected $searchableFields = [
        'title',
        'author',
    ];

    protected $with = ['speaker', 'invitedBy', 'confirmedBy'];

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
