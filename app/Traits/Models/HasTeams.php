<?php

namespace App\Traits\Models;

use App\Models\Role;
use App\Models\Team;
use InvalidArgumentException;

trait HasTeams
{
    public function hasTeams()
    {
        return $this->teams()->count() > 0;
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'user_teams')->orderBy('name', 'asc');
    }

    public function onTeam(Team $team)
    {
        return $this->teams->contains($team);
    }

    public function ownsTeam($team)
    {
        return $this->id && $team->owner_id && $this->id == $team->owner_id;
    }

    public function setRoleOnTeam($role, $team): self
    {
        return $this;
    }

    public function joinTeam(Team $team, $role = Role::MEMBER): self
    {
        if ($this->onTeam($team)) {
            $this->setRoleOnTeam($role, $team);
            $this->refresh();

            return $this;
        }

        $this->teams()->attach($team);
        $this->setRoleOnTeam($role, $team);
        $this->refresh();

        return $this;
    }

    public function switchToTeam($team)
    {
        if (!$this->onTeam($team)) {
            throw new InvalidArgumentException(__('teams.user_doesnt_belong_to_team'));
        }

        $this->current_team_id = $team->id;

        $this->save();
    }

    public function currentTeam()
    {
        if (is_null($this->current_team_id) && $this->hasTeams()) {
            $this->switchToTeam($this->teams->first());

            return $this->currentTeam();
        } elseif (!is_null($this->current_team_id)) {
            $currentTeam = $this->teams->find($this->current_team_id);

            return $currentTeam ?: $this->refreshCurrentTeam();
        }
    }

    public function getCurrentTeamAttribute()
    {
        return $this->currentTeam();
    }

    public function refreshCurrentTeam()
    {
        $this->current_team_id = null;

        $this->save();

        return $this->currentTeam();
    }
}
