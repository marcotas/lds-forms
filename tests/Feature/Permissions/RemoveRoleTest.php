<?php

namespace Tests\Feature\Permissions;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\PermissionAssertions;

class RemoveRoleTest extends TestCase
{
    use DatabaseTransactions, PermissionAssertions;

    /** @test */
    public function it_removes_a_global_role_if_no_team_is_passed()
    {
        $this->user  = $this->teamMember();
        $this->user->assignRole('global role');

        $this->assertDatabaseHas('user_roles', ['user_id' => $this->user->id]);
        $this->assertEquals(1, $this->user->roles()->globals()->count());
        $this->user->removeRole('global role');
        $this->assertEquals(0, $this->user->roles()->globals()->count());
    }

    /** @test */
    public function it_accepts_an_instance_of_role_to_be_removed()
    {
        $this->user  = $this->teamMember();
        $role        = create(Role::class);
        $this->user->assignRole($role);

        $this->assertDatabaseHas('user_roles', ['user_id' => $this->user->id, 'role_id' => $role->id]);
        $this->assertEquals(1, $this->user->roles()->globals()->count());
        $this->user->removeRole($role);
        $this->assertEquals(0, $this->user->roles()->globals()->count());
    }

    /** @test */
    public function it_removes_a_team_role_if_team_is_passed()
    {
        $this->user  = $this->teamMember();
        $team        = $this->team();
        $this->user->assignRole('team role', $team);

        $this->assertDatabaseHas('user_roles', ['user_id' => $this->user->id]);
        $this->assertEquals(1, $this->user->roles()->count());
        $this->user->removeRole('team role', $team);
        $this->assertEquals(0, $this->user->roles()->count());
    }

    /** @test */
    public function it_doenst_remove_a_global_role_with_the_same_name_of_a_team_role()
    {
        $this->user  = $this->teamMember();
        $team        = $this->team();
        $this->user->assignRole('some role'); // role global
        $this->user->assignRole('some role', $team); // team role

        $this->assertEquals(2, Role::count());
        $this->assertDatabaseHas('user_roles', ['user_id' => $this->user->id]);
        $this->assertEquals(2, $this->user->roles()->count());
        $this->assertEquals(1, $this->user->roles()->globals()->count());
        $this->assertEquals(1, $this->user->roles()->ofTeam($team)->count());
        $this->user->removeRole('some role', $team);
        $this->assertEquals(0, $this->user->roles()->ofTeam($team)->count());
        $this->assertEquals(1, $this->user->roles()->globals()->count());
    }

    /** @test */
    public function it_doesnt_remove_a_team_role_given_a_global_role_with_the_same_name()
    {
        $this->user  = $this->teamMember();
        $team        = $this->team();
        $this->user->assignRole('some role'); // role global
        $this->user->assignRole('some role', $team); // team role

        $this->assertEquals(2, Role::count());
        $this->assertDatabaseHas('user_roles', ['user_id' => $this->user->id]);
        $this->assertEquals(2, $this->user->roles()->count());
        $this->assertEquals(1, $this->user->roles()->globals()->count());
        $this->assertEquals(1, $this->user->roles()->ofTeam($team)->count());
        $this->user->removeRole('some role');
        $this->assertEquals(0, $this->user->roles()->globals()->count());
        $this->assertEquals(1, $this->user->roles()->ofTeam($team)->count());
    }
}
