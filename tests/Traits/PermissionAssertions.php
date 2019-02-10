<?php

namespace Tests\Traits;

use App\Models\Team;
use Illuminate\Support\Collection;

trait PermissionAssertions
{
    protected $user;

    public function assertNotPermitted($permission, Team $team = null): self
    {
        $permissionName = is_object($permission) ? $permission->name : $permission;
        $onTeam         = $team ? "on team '{$team->name}' ({$team->id})" : ' globally';
        $message        = "User should NOT have permission to '$permissionName' " . $onTeam;

        $this->assertFalse($this->user->can($permission, $team), $message);

        return $this;
    }

    public function assertPermitted($permission, Team $team = null): self
    {
        $permissionName = is_object($permission) ? $permission->name : $permission;
        $onTeam         = $team ? "on team '{$team->name}' ({$team->id})" : ' globally';
        $message        = "User should have permission to '$permissionName' " . $onTeam;

        $this->assertTrue($this->user->can($permission, $team), $message);

        return $this;
    }

    public function assertHasPermissions(array $permissionNames, Collection $permissions)
    {
        foreach ($permissionNames as $permissionName) {
            $this->assertTrue(
                $permissions->map->name->contains($permissionName),
                "Missing permission: $permissionName. \n\nCurrent permissions:\n" . $permissions->map->name . "\n"
            );
        }
    }
}
