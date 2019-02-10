<?php

namespace Tests;

use App\Models\Menu;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    /** @var \App\Models\User */
    protected $admin;
    /** @var \App\Models\User */
    protected $user;
    /** @var \App\Models\Team */
    protected $team;

    public function admin($userId = null): User
    {
        if ($this->admin) {
            return $this->admin;
        }

        $this->admin = $userId ? User::find($userId) : create(User::class);
        $admin       = $this->roleAdmin();
        $admin->givePermissionTo('*');
        $this->admin->assignRole($admin);

        return $this->admin->refresh();
    }

    public function roleAdmin()
    {
        return Role::firstOrCreate(['name' => 'admin']);
    }

    public function createTeam($user = null, $role = Role::OWNER) : Team
    {
        $user = $user ?: create(User::class);

        $team = create(Team::class, [
            'owner_id' => $user->id,
        ]);
        $user->joinTeam($team, $role);

        return $team->fresh();
    }

    public function team($teamId = null): Team
    {
        if ($this->team && !$teamId) {
            return $this->team;
        }

        return $this->team = Team::find($teamId) ?? create(Team::class);
    }

    public function teamOwner(): User
    {
        return $this->team()->owner;
    }

    public function teamMember($teamId = null): User
    {
        $this->user = create(User::class);
        $team       = Team::find($teamId) ?? $this->team();
        $this->user->joinTeam($team);

        return $this->user;
    }

    protected function createTestResponse($response) : TestResponse
    {
        return TestResponse::fromBaseResponse($response);
    }
}
