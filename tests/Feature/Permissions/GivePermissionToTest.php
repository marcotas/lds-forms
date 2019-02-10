<?php

namespace Tests\Feature\Permissions;

use App\Models\Permission;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\PermissionAssertions;

class GivePermissionToTest extends TestCase
{
    use DatabaseTransactions, PermissionAssertions;

    protected $user;

    public function user(User $user = null): self
    {
        $this->user = $user ?? $this->user ?? create(User::class);

        return $this;
    }

    public function givePermission($permission, ? Team $team = null): self
    {
        $this->user->givePermissionTo($permission, $team);

        return $this;
    }

    /** @test */
    public function it_gives_to_user_a_global_permission_when_no_team_is_provided()
    {
        $permission = 'do something';
        $this->user()
            ->assertNotPermitted($permission)
            ->givePermission($permission)
            ->assertPermitted($permission);
    }

    /** @test */
    public function it_creates_a_permission_if_it_doesnt_exist_in_database()
    {
        $permission = 'do something';
        $this->user()
            ->assertNotPermitted($permission)
            ->givePermission($permission);

        $this->assertDatabaseHas('permissions', [
            'name' => $permission,
        ]);
    }

    /** @test */
    public function it_creates_a_permission_in_lower_case_if_it_doesnt_exist_in_database()
    {
        $permission = 'Do Something';
        $this->user()
            ->assertNotPermitted($permission)
            ->givePermission($permission);

        $this->assertDatabaseHas('permissions', [
            'name' => 'do something',
        ]);
    }

    /** @test */
    public function it_doesnt_duplicate_in_database()
    {
        $permission = create(Permission::class);

        $this->user()
            ->assertNotPermitted($permission)
            ->givePermission($permission)
            ->givePermission($permission)
            ->givePermission($permission)
            ->assertPermitted($permission);

        $this->assertEquals(1, Permission::count());
        $this->assertEquals(1, $this->user->specificPermissions()->count());
    }

    /** @test */
    public function it_compares_the_permission_name_with_lowercase()
    {
        $permission = 'Do Something';

        $this->user()
            ->assertNotPermitted($permission)
            ->givePermission($permission)
            ->assertPermitted('dO soMeThiNg');

        $this->assertDatabaseHas('permissions', [
            'name' => 'do something',
        ]);
        $this->assertEquals(1, Permission::count());
    }

    /** @test */
    public function it_gives_a_user_a_permission_inside_a_team()
    {
        $permission = 'Do Something';
        $team       = $this->team();
        $team2      = $this->createTeam();

        $this->user($this->teamMember())
            ->assertNotPermitted($permission)
            ->givePermission($permission, $team)
            ->assertPermitted($permission, $team)
            ->assertNotPermitted($permission, $team2);

        $this->assertDatabaseHas('permissions', [
            'name' => 'do something',
        ]);
    }

    /** @test */
    public function user_receives_all_permissions_when_asterisc_is_given()
    {
        $permission = 'Do Something';
        $team       = $this->team();
        $team2      = $this->createTeam();

        $this->user($this->teamMember())
            ->assertNotPermitted($permission)
            ->givePermission('*', $team)
            ->assertPermitted($permission, $team)
            ->assertNotPermitted($permission, $team2);

        $this->assertDatabaseHas('permissions', [
            'name' => '*',
        ]);
    }
}
