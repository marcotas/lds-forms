<?php

namespace Tests\Feature\Services;

use App\Models\Service;
use App\Models\Team;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\TestResponse;

class DestroyServiceTest extends TestCase
{
    public function destroyService(Team $team, Service $service): TestResponse
    {
        return $this->deleteJson(route('services.destroy', compact('team', 'service')));
    }

    /** @test */
    public function it_soft_deletes()
    {
        Carbon::setTestNow(now());
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = create(Service::class, ['team_id' => $this->team()]);

        $this->actingAs($user)
            ->destroyService($this->team(), $service)
            ->assertSuccessful();

        $this->assertDatabaseHas($service->getTable(), [
            'id'         => $service->id,
            'deleted_at' => now(),
        ]);
    }

    /** @test */
    public function it_is_permitted_to_who_can_manage_services()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('another permission');
        $service = create(Service::class, ['team_id' => $this->team()]);

        $this->actingAs($user)
            ->destroyService($this->team(), $service)
            ->assertForbidden();

        $this->assertDatabaseHas($service->getTable(), [
            'id'         => $service->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function is_should_soft_deletes_all_its_dependents()
    {
    }
}
