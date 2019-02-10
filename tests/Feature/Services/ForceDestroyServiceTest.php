<?php

namespace Tests\Feature\Services;

use App\Models\Service;
use App\Models\Team;
use Tests\TestCase;
use Tests\TestResponse;

class ForceDestroyServiceTest extends TestCase
{
    public function forceDestroy(Team $team, Service $service): TestResponse
    {
        return $this->deleteJson(route('services.force-destroy', compact('team', 'service')));
    }

    /** @test */
    public function it_should_remove_from_database()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = create(Service::class, ['team_id' => $this->team()]);

        $this->actingAs($user)
            ->forceDestroy($this->team(), $service)
            ->assertSuccessful();

        $this->assertDatabaseMissing($service->getTable(), ['id' => $service->id]);
    }

    /** @test */
    public function it_is_forbidden_when_cant_manage_services()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('another permission');
        $service = create(Service::class, ['team_id' => $this->team()]);

        $this->actingAs($user)
            ->forceDestroy($this->team(), $service)
            ->assertForbidden();
    }

    /** @test */
    public function it_cannot_force_destroy_of_another_team()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = create(Service::class);

        $this->actingAs($user)
            ->forceDestroy($this->team(), $service)
            ->assertNotFound();
    }

    /** @test */
    public function it_force_deletes_all_its_dependents()
    {
    }
}
