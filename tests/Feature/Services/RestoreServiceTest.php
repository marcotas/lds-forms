<?php

namespace Tests\Feature\Services;

use App\Models\Service;
use App\Models\Team;
use Tests\TestCase;
use Tests\TestResponse;

class RestoreServiceTest extends TestCase
{
    public function restoreService(Team $team, Service $service): TestResponse
    {
        return $this->postJson(route('services.restore', compact('team', 'service')));
    }

    /** @test */
    public function it_restores_from_database()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = create(Service::class, ['team_id' => $this->team(), 'deleted_at' => now()]);

        $this->actingAs($user)
            ->restoreService($this->team(), $service)
            ->assertSuccessful();

        $this->assertDatabaseHas($service->getTable(), [
            'id'         => $service->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_is_forbidden_for_who_cant_manange_services()
    {
        $user    = $this->teamMember();
        $service = create(Service::class, ['team_id' => $this->team(), 'deleted_at' => now()]);

        $this->actingAs($user)
            ->restoreService($this->team(), $service)
            ->assertForbidden();
    }

    /** @test */
    public function it_cannot_restore_from_another_team()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = create(Service::class, ['deleted_at' => now()]);

        $this->actingAs($user)
            ->restoreService($this->team(), $service)
            ->assertNotFound();
    }
}
