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

class RevokePermissionTest extends TestCase
{
    use DatabaseTransactions, PermissionAssertions;

    /** @test */
    public function it_removes_a_global_permission_if_no_team_passed()
    {
        $this->user  = create(User::class);

        $this->user->givePermissionTo('something');

        $this->assertPermitted('something');
        $this->user->revokePermission('something');
        $this->assertNotPermitted('something');
    }

    /** @test */
    public function it_should_not_remove_team_permissions_when_removing_the_global_ones()
    {
        $this->user = create(User::class);
        $team1      = create(Team::class);

        $this->user->givePermissionTo('something');
        $this->user->givePermissionTo('something', $team1);

        $this->assertPermitted('something');
        $this->assertPermitted('something', $team1);

        $this->user->revokePermission('something');

        $this->assertPermitted('something', $team1);
        $this->assertNotPermitted('something');
    }

    /** @test */
    public function it_removes_a_team_permission_when_passing_a_team()
    {
        $this->user = create(User::class);
        $team1      = create(Team::class, ['name' => 'Team 1']);
        $this->user->joinTeam($team1);

        $this->user->givePermissionTo('something', $team1);

        $this->assertPermitted('something', $team1);

        $removed = $this->user->revokePermission('something', $team1);

        $this->assertEquals(1, $removed);
        $this->assertNotPermitted('something', $team1);
        $this->assertCount(0, $this->user->permissions);
    }

    /** @test */
    public function it_doenst_do_anything_if_permission_doenst_exist()
    {
        $this->user = create(User::class);
        $this->user->givePermissionTo('something');

        $this->assertPermitted('something');

        $removed = $this->user->revokePermission('non related permission');

        $this->assertEquals(0, $removed);
        $this->assertPermitted('something');
    }

    /** @test */
    public function it_doesnt_do_anything_if_try_to_revoke_a_permission_given_by_a_role()
    {
        $this->user = create(User::class);
        $role       = create(Role::class);
        $role->givePermissionTo('something');
        $this->user->assignRole($role);

        $this->assertPermitted('something');

        $removed = $this->user->revokePermission('something');

        $this->assertEquals(0, $removed);
        $this->assertPermitted('something');
    }
}
