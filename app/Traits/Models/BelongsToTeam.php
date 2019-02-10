<?php

namespace App\Traits\Models;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTeam
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeOfTeam(Builder $builder, $team = null): Builder
    {
        $teamId = is_object($team) ? $team->id : $team;

        return $builder->whereTeamId($teamId);
    }

    public function sameTeamOf(User $user): bool
    {
        return $user->teams->map->id->contains($this->team_id);
    }
}
