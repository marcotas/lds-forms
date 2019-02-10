<?php

namespace App\Traits\Models;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

trait HasRoleAndPermissions
{
    public function can($permission, $team = [])
    {
        $team = is_array($team) ? null : $team;

        return $this->hasPermissionTo($permission, $team);
    }

    public function cant($permission, $team = [])
    {
        return !$this->can($permission, $team);
    }

    public function cannot($permission, $team = [])
    {
        return !$this->can($permission, $team);
    }

    public function hasPermissionTo($permission, $team = null): bool
    {
        $permission          = $this->getPermission($permission);
        $globalPermissions   = $this->global_permissions;
        $teamPermissions     = $this->permissionsOnTeam($team);
        $hasGlobalPermission = $globalPermissions->contains($permission);
        $hasTeamPermission   = $teamPermissions->contains($permission);
        $doAnythingInTeam    = $teamPermissions->map->name->contains('*');
        $doAnythingGlobally  = $globalPermissions->map->name->contains('*');

        return $hasGlobalPermission || $hasTeamPermission || $doAnythingGlobally
            || (!$doAnythingGlobally && $doAnythingInTeam);
    }

    public function givePermissionTo($permission, $team = null): self
    {
        $name = $permission;
        if ($permission instanceof Permission) {
            $name = $permission->name;
        }

        $permission = Permission::firstOrCreate(compact('name'));

        $team_id = is_object($team) ? $team->id : $team;

        if ($this->specific_permissions
            ->where('id', $permission->id)
            ->where('pivot.team_id', $team_id)
            ->isEmpty()
        ) {
            $this->specificPermissions()->attach($permission, compact('team_id'));
        }
        $this->refresh();

        return $this;
    }

    public function revokePermission($permission, $team = null)
    {
        if (!($permission = $this->getPermission($permission))) {
            return;
        }

        $teamId = is_object($team) ? $team->id : $team;

        return $this->specificPermissions()
            ->where('id', $permission->id)
            ->wherePivot('team_id', $teamId)
            ->detach($permission);
    }

    public function getPermission($permission): ? Permission
    {
        return is_object($permission) ? $permission : Permission::named($permission)->first();
    }

    public function specificPermissions(): MorphToMany
    {
        return $this->morphToMany(Permission::class, 'permissible')->withPivot('team_id')->orderBy('name');
    }

    public function getRolePermissions($role = null, $team = null): Collection
    {
        $role = $role ?? $this->role;

        if (!$role) {
            return collect();
        }

        $roleName = is_object($role) ? $role->name : $role;
        $roles    = Role::named($roleName);
        $team ? $roles->ofTeam($team) : $roles->global();
        $role = $roles->first();

        if (!$role) {
            return collect();
        }

        return $role->specific_permissions;
    }

    public function getRolePermissionsAttribute(): Collection
    {
        return optional($this->roleOnCurrentTeam())->specific_permissions ?? collect();
    }

    public function getTeamPermissionsAttribute(): Collection
    {
        return $this->permissionsOnTeam($this->current_team)
            ->merge($this->role_permissions);
    }

    public function getSpecificPermissionsAttribute()
    {
        return $this->specificPermissions()->get();
    }

    public function getPermissionsAttribute()
    {
        return $this->team_permissions
            ->concat($this->global_permissions)
            ->concat($this->specific_permissions)
            ->unique(function ($permission) {
                return $permission['id'] . $permission['pivot']['team_id'];
            });
    }

    public function getAllPermissionsAttribute()
    {
        return $this->global_permissions->concat($this->specific_permissions)->concat(
            $this->roles()->get()->map->specificPermissions->flatten()
        )->unique('id');
    }

    public function getGlobalPermissionsAttribute(): Collection
    {
        return $this->specificPermissions()->wherePivot('team_id', null)->get()
            ->merge(
                $this->roles()->globals()->get()->map->specificPermissions->flatten()->unique('id')
            );
    }

    public function permissionsOnTeam($team = null): Collection
    {
        $team = is_object($team) ? $team : Team::find($team);

        if ($team && !($team instanceof Team)) {
            throw new InvalidArgumentException('Team parameter is not a valid ID or not of type ' . Team::class);
        }

        $teamId = is_object($team) ? $team->id : $team;

        return $this->specificPermissions()->wherePivot('team_id', $teamId)->get();
    }

    public function syncPermissions(array $permissions): self
    {
        return DB::transaction(function () use ($permissions) {
            $teamPermissions = collect($permissions)->filter(function ($permission) {
                return array_get($permission, 'pivot.team_id');
            })->unique(function ($permission) {
                return $permission['id'] . $permission['pivot']['team_id'];
            })->toArray();

            $globalPermissions = collect($permissions)->filter(function ($permission) {
                return is_null(array_get($permission, 'pivot.team_id'));
            })->unique('id')->toArray();

            $newSpecificPermissions = $teamPermissions + $globalPermissions;

            $this->specificPermissions()->detach();

            foreach ($newSpecificPermissions as $permission) {
                $this->specificPermissions()->attach(
                    $permission['id'],
                    ['team_id' => array_get($permission, 'pivot.team_id')]
                );
            }

            $this->refresh();

            return $this;
        });
    }

    // Role Methods

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')->orderBy('name');
    }

    public function roleOnTeam($team = null): ? Role
    {
        $team = is_object($team) ? $team : Team::find($team);

        return $this->roles()->whereTeamId(optional($team)->id)->first();
    }

    public function roleOnCurrentTeam(): ? Role
    {
        $teamId = optional($this->current_team)->id;
        $team   = Team::find($teamId);

        return $this->roleOnTeam($team) ?? $this->roles->first();
    }

    public function getRole(): ? Role
    {
        return $this->roleOnCurrentTeam();
    }

    public function role(): ? Role
    {
        return $this->roleOnCurrentTeam();
    }

    public function getRoleAttribute()
    {
        return optional($this->role())->name;
    }

    public function isA($role, $team = null): bool
    {
        return $this->hasRole($role, $team);
    }

    public function isAn($role, $team = null): bool
    {
        return $this->isA($role, $team);
    }

    public function assignRole($role, ? Team $team = null): self
    {
        $this->roles()->ofTeam($team)->delete();

        $teamId = optional($team)->id;
        $role   = $role instanceof Role ? $role : Role::firstOrCreate([
            'name'    => strtolower($role),
            'team_id' => $teamId
        ]);
        $role->update(['team_id' => $teamId]);
        $this->roles()->attach($role);
        $this->refresh();

        return $this;
    }

    public function hasRole($role, $team = null)
    {
        $roleName = is_object($role) ? $role->name : $role;
        $roles    = $this->roles()->named($roleName);

        $team ? $roles->ofTeam($team) : $roles->globals();

        return $roles->exists();
    }

    public function removeRole($role, $team = null)
    {
        $roleName = is_object($role) ? strtolower($role->name) : strtolower($role);

        $roles = $this->roles()->named($roleName);
        $team ? $roles->ofTeam($team) : $roles->globals();

        if (!$roles->exists()) {
            return null;
        }

        return $roles->delete();
    }
}
