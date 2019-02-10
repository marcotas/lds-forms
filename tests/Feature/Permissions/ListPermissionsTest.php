<?php

namespace Tests\Feature\Permissions;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\Traits\PermissionAssertions;

class ListPermissionsTest extends TestCase
{
    use DatabaseTransactions, PermissionAssertions;

    protected $team1;
    protected $team2;
    protected $globalRole;
    protected $roleTeam1;
    protected $roleTeam2;

    protected function setUp()
    {
        parent::setUp();

        $this->user  = create(User::class);
        $this->team1 = create(Team::class, ['name' => 'Team 1']);
        $this->team2 = create(Team::class, ['name' => 'Team 2']);
        $this->user->joinTeam($this->team1);
        $this->user->joinTeam($this->team2);
        $this->user->forceFill(['current_team_id' => $this->team1->id])->save();

        $this->globalRole = create(Role::class);
        $this->globalRole->givePermissionTo('do a global thing');

        $this->roleTeam1 = create(Role::class, ['team_id' => $this->team1]);
        $this->roleTeam1->givePermissionTo('do something in team 1');

        $this->roleTeam2 = create(Role::class, ['team_id' => $this->team2]);
        $this->roleTeam2->givePermissionTo('do something in team 2');

        $this->user->assignRole($this->globalRole);
        $this->user->assignRole($this->roleTeam1, $this->team1);
        $this->user->assignRole($this->roleTeam2, $this->team2);
        $this->user->givePermissionTo('specific global permission');
        $this->user->givePermissionTo('specific permission in team 1', $this->team1);
        $this->user->givePermissionTo('specific permission in team 2', $this->team2);
    }

    /** @test */
    public function role_permissions_should_return_all_permissions_of_role()
    {
        $this->assertHasPermissions([
            'do something in team 1',
        ], $this->user->role_permissions);
    }

    /** @test */
    public function team_permissions_return_permissions_in_current_team_with_role_permissions()
    {
        $this->assertHasPermissions([
            'do something in team 1',
            'specific permission in team 1',
        ], $this->user->team_permissions);
    }

    /** @test */
    public function global_permissions_return_global_permissions_with_global_role_permissions()
    {
        $this->assertHasPermissions([
            'do a global thing',
            'specific global permission',
        ], $this->user->global_permissions);
    }

    /** @test */
    public function specific_permissions_return_all_granted_permissions_without_any_scope()
    {
        $this->assertHasPermissions([
            'specific global permission',
            'specific permission in team 1',
            'specific permission in team 2',
        ], $this->user->specific_permissions);
    }

    /** @test */
    public function permissions_return_team_permissions_and_global_permissions()
    {
        $this->assertHasPermissions([
            'do a global thing',
            'specific global permission',
            'specific permission in team 1',
            'do something in team 1',
        ], $this->user->permissions);

        $this->user->forceFill(['current_team_id' => $this->team2->id])->save();

        $this->assertHasPermissions([
            'do a global thing',
            'specific global permission',
            'specific permission in team 2',
            'do something in team 2',
        ], $this->user->permissions);
    }

    /** @test */
    public function all_permissions_return_all_global_permissions_and_in_all_teams()
    {
        $this->assertHasPermissions([
            'do a global thing',
            'specific global permission',
            'specific permission in team 1',
            'specific permission in team 2',
            'do something in team 1',
            'do something in team 2',
        ], $this->user->all_permissions);
    }
}
