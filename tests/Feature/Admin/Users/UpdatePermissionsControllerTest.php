<?php

namespace Tests\Feature\Admin\Users;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\TestResponse;

class UpdatePermissionsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function updatePermissions(Collection $permissions, User $user): TestResponse
    {
        return $this->putJson(route('admin.users.update-permissions', $user), compact('permissions'));
    }

    /** @test */
    public function it_syncs_teams_and_permissions_to_an_user()
    {
        $admin       = $this->admin();
        $user        = create(User::class);
        $permissions = collect();
        $permission  = create(Permission::class);
        $team        = $this->team();
        $user->joinTeam($team);
        $permission->pivot = [
            'team_id' => $team->id,
        ];
        $permissions->push($permission);

        $this->assertTrue($admin->can($permission, $team));
        $this->assertFalse($user->can($permission, $team));
        $this->actingAs($admin)
            ->updatePermissions($permissions, $user)
            ->assertSuccessful();

        $user->refresh();

        $this->assertTrue($user->can($permission, $team));
        $this->assertTrue($user->permissions->contains($permission));
        $this->assertDatabaseHas('permissibles', [
            'permissible_id'   => $user->id,
            'permissible_type' => $user->getTable(),
            'permission_id'    => $permission->id,
            'team_id'          => $team->id,
        ]);
    }

    /** @test */
    public function it_syncs_global_permissions_when_passing_team_id_null_in_pivot_data()
    {
        $admin             = $this->admin();
        $user              = create(User::class);
        $permissions       = collect();
        $permission        = create(Permission::class);
        $permission->pivot = ['team_id' => null];
        $permissions->push($permission);

        $this->assertTrue($admin->can($permission));
        $this->assertFalse($user->can($permission));

        $this->actingAs($admin)
            ->updatePermissions($permissions, $user)
            ->assertSuccessful();

        $user->refresh();

        $this->assertTrue($user->can($permission));
        $this->assertTrue($user->permissions->contains($permission));
        $this->assertDatabaseHas('permissibles', [
            'permissible_id'   => $user->id,
            'permissible_type' => $user->getTable(),
            'permission_id'    => $permission->id,
        ]);
    }

    /** @test */
    public function it_permit_the_permissions_attribute_to_be_an_empty_array_to_remove_all_permissions()
    {
        $admin             = $this->admin();
        $user              = create(User::class);
        $permissions       = collect();
        $permission        = create(Permission::class);
        $permission->pivot = ['team_id' => null];
        $permissions->push($permission);
        $user->syncPermissions($permissions->toArray());

        $this->assertTrue($user->specific_permissions->contains($permission));
        $this->assertTrue($admin->can($permission));
        $this->assertTrue($user->can($permission));

        $this->actingAs($admin)
            ->updatePermissions(collect([]), $user)
            ->assertSuccessful();

        $user->refresh();

        $this->assertFalse($user->can($permission));
        $this->assertFalse($user->specific_permissions->contains($permission));
        $this->assertDatabaseMissing('permissibles', [
            'permissible_id'   => $user->id,
            'permissible_type' => $user->getTable(),
            'permission_id'    => $permission->id,
        ]);
    }

    /** @test */
    public function it_requires_permission_id_attribute_to_be_present()
    {
        $admin             = $this->admin();
        $user              = create(User::class);
        $permissions       = collect();
        $permission        = make(Permission::class);
        $permission->pivot = ['team_id' => null];
        $permissions->push($permission);

        $this->assertTrue($admin->can($permission));
        $this->assertFalse($user->can($permission));

        $this->actingAs($admin)
            ->updatePermissions($permissions, $user)
            ->assertUnprocessableEntity()
            ->assertJsonHasFragmentError('permissions.0.id', 'O campo ID da permissão é obrigatório.');
    }

    /** @test */
    public function it_requires_permission_id_to_exists()
    {
        $admin             = $this->admin();
        $user              = create(User::class);
        $permissions       = collect();
        $permission        = create(Permission::class);
        $permission->pivot = ['team_id' => null];
        $permissions->push($permission);
        $permission->delete();

        $this->assertDatabaseMissing($permission->getTable(), [
            'id' => $permission->id,
        ]);

        $this->actingAs($admin)
            ->updatePermissions($permissions, $user)
            ->assertUnprocessableEntity()
            ->assertJsonHasFragmentError('permissions.0.id', 'O campo ID da permissão selecionado é inválido.');
    }

    /** @test */
    public function it_can_be_done_by_admin()
    {
        $admin       = $this->admin();
        $user        = create(User::class);
        $permissions = collect();

        $this->assertTrue($admin->isAn('admin'));

        $this->actingAs($admin)
            ->updatePermissions($permissions, $user)
            ->assertSuccessful();
    }

    /** @test */
    public function it_cannot_be_done_by_anyone_else()
    {
        $member      = $this->teamMember();
        $user        = create(User::class);
        $permissions = collect();

        $this->assertFalse($member->isAn('admin'));

        $this->actingAs($member)
            ->updatePermissions($permissions, $user)
            ->assertRedirect();
    }

    /** @test */
    public function it_has_unique_permissions_with_id_and_team_id()
    {
        $team  = $this->team();
        $admin = $this->admin();
        $user  = create(User::class);
        $user->joinTeam($team);

        $permission  = create(Permission::class, ['name' => 'some permission']);
        $permission2 = clone $permission;
        $permission3 = clone $permission;
        // global permission
        $permission->pivot = ['team_id' => null];
        // duplicated permission with team scope
        $permission2->pivot = ['team_id' => $this->team()->id];
        $permission3->pivot = ['team_id' => $this->team()->id];
        $permissions        = collect([$permission, $permission2, $permission3]);

        $this->assertTrue($admin->can($permission));
        $this->assertFalse($user->can($permission));

        $this->actingAs($admin)
            ->updatePermissions($permissions, $user)
            ->assertSuccessful();

        $user->refresh();

        $this->assertTrue($user->can($permission));
        $this->assertTrue($user->can($permission2, $team));
        $this->assertTrue($user->permissions->contains($permission));
        $this->assertTrue($user->permissions->contains($permission2));
        $this->assertDatabaseHas('permissibles', [
            'permissible_id'   => $user->id,
            'permissible_type' => $user->getTable(),
            'permission_id'    => $permission->id,
            'team_id'          => null,
        ]);
        $this->assertDatabaseHas('permissibles', [
            'permissible_id'   => $user->id,
            'permissible_type' => $user->getTable(),
            'permission_id'    => $permission->id,
            'team_id'          => $team->id,
        ]);
    }
}
