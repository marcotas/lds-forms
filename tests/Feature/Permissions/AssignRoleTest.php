<?php

namespace Tests\Feature\Permissions;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssignRoleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_role_if_doesnt_exists()
    {
        $user = create(User::class);

        $user->assignRole('admin');

        $this->assertDatabaseHas('roles', [
            'name'    => 'admin',
            'team_id' => null,
        ]);
        $this->assertEquals(1, Role::count());
    }

    /** @test */
    public function it_can_receive_a_string_with_the_name_of_the_role()
    {
        $user = create(User::class);

        $user->assignRole('jermiah');

        $this->assertDatabaseHas('roles', [
            'name'    => 'jermiah',
            'team_id' => null,
        ]);
        $this->assertEquals(1, Role::count());
    }

    /** @test */
    public function it_can_receive_an_instance_of_role()
    {
        $user = create(User::class);
        $role = create(Role::class);

        $user->assignRole($role);

        $this->assertDatabaseHas('roles', [
            'id'   => $role->id,
            'name' => $role->name,
        ]);
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
        $this->assertEquals(1, Role::count());
    }

    /** @test */
    public function it_is_global_if_no_team_passed()
    {
        $user = create(User::class);
        $role = create(Role::class);

        $user->assignRole($role);

        $this->assertDatabaseHas('roles', [
            'id'      => $role->id,
            'name'    => $role->name,
            'team_id' => null,
        ]);
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
        $role->refresh();
        $this->assertEquals(1, Role::count());
        $this->assertTrue($role->is_global);
    }

    /** @test */
    public function it_is_in_team_scope_if_team_passed()
    {
        $user = create(User::class);
        $team = create(Team::class);
        $role = create(Role::class, ['team_id' => $team->id]);
        $user->joinTeam($team);

        $user->assignRole($role, $team);

        $this->assertDatabaseHas('roles', [
            'id'      => $role->id,
            'name'    => $role->name,
            'team_id' => $team->id,
        ]);
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
        $role->refresh();
        $this->assertEquals(1, $user->roles()->count());
        $this->assertFalse($role->is_global);
    }

    /** @test */
    public function it_is_case_insensitive()
    {
        $user = create(User::class);

        $user->assignRole('SoMe rOlE');

        $this->assertDatabaseHas('roles', [
            'name' => 'some role',
        ]);
        $this->assertEquals(1, $user->roles()->count());
        $this->assertEquals(1, Role::count());
    }

    /** @test */
    public function it_doesnt_duplicate_role()
    {
        $user = create(User::class);

        $user->assignRole('some role');
        $user->assignRole('SOME ROLE');
        $user->assignRole('SoMe rOlE');

        $this->assertDatabaseHas('roles', [
            'name' => 'some role',
        ]);
        $this->assertEquals(1, $user->roles()->count());
        $this->assertEquals(1, Role::count());
    }
}
