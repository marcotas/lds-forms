<?php

namespace Tests\Feature\Permissions;

use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HasPermissionToTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_checks_permissions_in_global_scope_when_no_team_is_passed()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('do something');

        $this->assertTrue($user->hasPermissionTo('do something'));
    }

    /** @test */
    public function it_checks_permissions_in_team_scope_of_the_team_passed()
    {
        $user = $this->teamMember();
        $team = $this->team();

        $user->givePermissionTo('do something', $team);

        $this->assertTrue($user->hasPermissionTo('do something', $team));
    }

    /** @test */
    public function it_returns_true_if_have_it_in_global_scope_even_if_doesnt_have_in_the_team_scope()
    {
        $user = $this->teamMember();
        $team = $this->team();

        $user->givePermissionTo('do something');

        $this->assertTrue($user->hasPermissionTo('do something'));
        $this->assertTrue($user->hasPermissionTo('do something', $team));
    }

    /** @test */
    public function it_returns_false_if_checking_globally_but_is_only_in_a_team_scope()
    {
        $user = $this->teamMember();
        $team = $this->team();

        $user->givePermissionTo('do something', $team);

        $this->assertTrue($user->hasPermissionTo('do something', $team));
        $this->assertFalse(
            $user->hasPermissionTo('do something'),
            "User SHOUDN'T have global permission that was given only in a team."
        );
    }

    /** @test */
    public function it_can_receive_an_instance_of_permission()
    {
        $user       = $this->teamMember();
        $team       = $this->team();
        $permission = create(Permission::class);

        $user->givePermissionTo($permission);

        $this->assertTrue($user->hasPermissionTo($permission, $team));
        $this->assertTrue($user->hasPermissionTo($permission));
    }

    /** @test */
    public function it_always_returns_true_if_has_asteristic_permission_in_a_team_scope()
    {
        $user = $this->teamMember();
        $team = $this->team();

        $user->givePermissionTo('*', $team);

        $this->assertTrue($user->hasPermissionTo('*', $team));
        $this->assertTrue($user->hasPermissionTo('do anything', $team));
        $this->assertFalse(
            $user->hasPermissionTo('do another thing'),
            "User SHOUDN'T have global permission that was given only in a team."
        );
    }

    /** @test */
    public function it_always_returns_true_if_has_asteristic_permission_in_global_scope_independantly_of_the_team()
    {
        $user = $this->teamMember();
        $team = $this->team();

        $user->givePermissionTo('*');

        $this->assertTrue($user->hasPermissionTo('*', $team));
        $this->assertTrue($user->hasPermissionTo('do anything on team', $team));
        $this->assertTrue($user->hasPermissionTo('do another thing'));
    }

    /** @test */
    public function method_can_is_an_alias()
    {
        $user = $this->teamMember();
        $team = $this->team();

        $user->givePermissionTo('*');

        $this->assertTrue($user->hasPermissionTo('*', $team));
        $this->assertTrue($user->hasPermissionTo('do anything on team', $team));
        $this->assertTrue($user->hasPermissionTo('do another thing'));
        $this->assertTrue($user->can('*', $team));
        $this->assertTrue($user->can('do anything on team', $team));
        $this->assertTrue($user->can('do another thing'));
    }
}
