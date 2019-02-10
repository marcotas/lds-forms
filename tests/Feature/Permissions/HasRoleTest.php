<?php

namespace Tests\Feature\Permissions;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HasRoleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_checks_if_user_has_role_globally_when_no_team_is_passed()
    {
        $user = $this->teamMember();
        $team = $this->team();

        $user->assignRole('some role');

        $this->assertTrue($user->hasRole('some role'));
        $this->assertFalse($user->hasRole('some role', $team));
        $this->assertDatabaseHas('user_roles', ['user_id' => $user->id]);
    }

    /** @test */
    public function it_checks_if_user_has_role_on_the_team_passed()
    {
        $user = $this->teamMember();
        $team = $this->team();

        $user->assignRole('some role', $team);

        $this->assertTrue($user->hasRole('some role', $team));
        $this->assertFalse($user->hasRole('some role'));
        $this->assertDatabaseHas('user_roles', ['user_id' => $user->id]);
    }

    /** @test */
    public function it_has_is_a_and_is_an_aliases()
    {
        $user = $this->teamMember();
        $team = $this->team();

        $user->assignRole('some role', $team);

        $this->assertTrue($user->isA('some role', $team));
        $this->assertFalse($user->isA('some role'));
        $this->assertTrue($user->isAn('some role', $team));
        $this->assertFalse($user->isAn('some role'));
        $this->assertDatabaseHas('user_roles', ['user_id' => $user->id]);
    }

    /** @test */
    public function it_only_checks_role_name_when_an_instance_of_role_is_passed()
    {
        $user = $this->teamMember();
        $team = $this->team();
        $role = create(Role::class);

        $user->assignRole($role->name, $team);

        $this->assertTrue($user->hasRole($role, $team));
        $this->assertFalse($user->hasRole($role));
        $this->assertTrue($user->hasRole($role->name, $team));
        $this->assertFalse($user->hasRole($role->name));
        $this->assertDatabaseHas('user_roles', ['user_id' => $user->id]);
        $this->assertEquals(2, Role::count());
    }
}
