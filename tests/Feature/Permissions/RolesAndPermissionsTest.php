<?php

namespace Tests\Feature\Permissions;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\PermissionAssertions;

class RolesAndPermissionsTest extends TestCase
{
    use DatabaseTransactions, PermissionAssertions;

    /** @test */
    public function method_all_permissions_returns_all_permissions_in_roles_and_assigned_of_all_teams()
    {
        $team1 = create(Team::class, ['name' => 'Team 1']);
        $team2 = create(Team::class, ['name' => 'Team 2']);

        $user = create(User::class);
        $user->joinTeam($team1);
        $user->joinTeam($team2);

        // Permissions
        $permissionGlobal     = 'do a global thing';
        $permissionOnTeam1    = 'do something on team 1';
        $permissionOnTeam2    = 'do another thing on team 2';
        $rolePermission       = 'role can do something too';
        $globalRolePermission = 'global role can destroy everything';

        $user->givePermissionTo($permissionGlobal);
        $user->givePermissionTo($permissionOnTeam1, $team1);
        $user->givePermissionTo($permissionOnTeam2, $team2);

        // Roles
        $globalRole  = create(Role::class);
        $globalRole->givePermissionTo($globalRolePermission);
        $roleOnTeam1 = create(Role::class, ['team_id' => $team1]);
        $roleOnTeam1->givePermissionTo($rolePermission);
        $roleOnTeam2 = create(Role::class, ['team_id' => $team2]);
        $roleOnTeam2->givePermissionTo($rolePermission);

        $user->assignRole($globalRole);
        $user->assignRole($roleOnTeam1, $team1);
        $user->assignRole($roleOnTeam2, $team2);

        $user->refresh();

        $this->assertCount(3, $user->roles);
    }

    /** @test */
    public function cases_of_giving_permissions_to_user()
    {
        $team1 = create(Team::class, ['name' => 'Team 1']);
        $team2 = create(Team::class, ['name' => 'Team 2']);

        $this->user = create(User::class);
        $this->user->joinTeam($team1);
        $this->user->joinTeam($team2);

        $permissionOnTeam1 = 'do something on team 1';
        $permissionOnTeam2 = 'do another thing on team 2';
        $permissionGlobal  = 'do a global thing';

        $this->user->givePermissionTo($permissionOnTeam1, $team1);
        $this->user->givePermissionTo($permissionOnTeam2, $team2);
        $this->user->givePermissionTo($permissionGlobal);

        $this->assertPermitted($permissionOnTeam1, $team1);
        $this->assertPermitted($permissionOnTeam2, $team2);
        $this->assertPermitted($permissionGlobal);
        $this->assertPermitted($permissionGlobal, $team1);
        $this->assertPermitted($permissionGlobal, $team2);

        $this->assertNotPermitted($permissionOnTeam1, $team2);
        $this->assertNotPermitted($permissionOnTeam1);
        $this->assertNotPermitted($permissionOnTeam2, $team1);
        $this->assertNotPermitted($permissionOnTeam2);
    }

    /** @test */
    public function give_all_permissions_to_user_globally()
    {
        $team1 = create(Team::class, ['name' => 'Team 1']);

        $this->user = create(User::class);
        $this->user->joinTeam($team1);

        $this->user->givePermissionTo('*');

        $this->assertPermitted('do whatever he wants');
        $this->assertPermitted('do whatever he wants in any team', $team1);
    }

    /** @test */
    public function give_all_permissions_to_user_only_in_a_team_scope()
    {
        $team1 = create(Team::class, ['name' => 'Team 1']);
        $team2 = create(Team::class, ['name' => 'Team 2']);

        $this->user = create(User::class);
        $this->user->joinTeam($team1);
        $this->user->joinTeam($team2);

        $this->user->givePermissionTo('*', $team1);
        $this->user->givePermissionTo('one thing in team 2', $team2);

        $this->assertPermitted('do whatever he wants in team 1', $team1);
        $this->assertPermitted('one thing in team 2', $team2);
        $this->assertNotPermitted('do whatever he wants in team 2', $team2);
    }
}
