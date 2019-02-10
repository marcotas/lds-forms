<?php

namespace Tests\Feature\Services;

use App\Models\Service;
use Tests\TestCase;
use Tests\TestResponse;

class UpdateServiceTest extends TestCase
{
    public function updateService(int $service, array $attributes): TestResponse
    {
        return $this->putJson(route('services.update', compact('service')), $attributes);
    }

    /** @test */
    public function it_should_update_the_database()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service            = create(Service::class, ['team_id' => $this->team()]);
        $attributes         = $service->toArray();
        $attributes['name'] = 'Updated name';

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertSuccessful();

        $this->assertDatabaseHas($service->getTable(), $attributes);
    }

    /** @test */
    public function it_is_allowed_for_who_can_manage_services()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('another permission');
        $service            = create(Service::class, ['team_id' => $this->team()]);

        $this->actingAs($user)
            ->updateService($service->id, $service->toArray())
            ->assertForbidden();
    }

    /** @test */
    public function it_is_not_allowed_to_teams_not_join()
    {
        $user        = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service            = create(Service::class);
        $attributes         = $service->toArray();
        $attributes['name'] = 'Updated name';

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertNotFound();
    }

    /** @test */
    public function name_field_uniqueness_is_ignored_on_update()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service    = create(Service::class, ['team_id' => $this->team()]);
        $attributes = $service->toArray();

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertSuccessful();

        $this->assertDatabaseHas($service->getTable(), $attributes);
    }

    /** @test */
    public function name_field_is_required()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service            = create(Service::class, ['team_id' => $this->team()]);
        $attributes         = $service->toArray();
        $attributes['name'] = '';

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertJsonHasFragmentError('name', 'O campo nome é obrigatório.');
    }

    /** @test */
    public function name_field_has_max_of_255_caracters()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service            = create(Service::class, ['team_id' => $this->team()]);
        $attributes         = $service->toArray();
        $attributes['name'] = str_random(256);

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertJsonHasFragmentError('name', 'O campo nome não pode ser superior a 255 caracteres.');
    }

    /** @test */
    public function commission_field_is_required()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service                  = create(Service::class, ['team_id' => $this->team()]);
        $attributes               = $service->toArray();
        $attributes['commission'] = '';

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertJsonHasFragmentError('commission', 'O campo comissão é obrigatório.');
    }

    /** @test */
    public function commission_field_has_min_of_0()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service                  = create(Service::class, ['team_id' => $this->team()]);
        $attributes               = $service->toArray();
        $attributes['commission'] = -1;

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertJsonHasFragmentError('commission', 'O campo comissão deve ser pelo menos 0.');
    }

    /** @test */
    public function commission_field_has_max_of_100()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service                  = create(Service::class, ['team_id' => $this->team()]);
        $attributes               = $service->toArray();
        $attributes['commission'] = 101;

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertJsonHasFragmentError('commission', 'O campo comissão não pode ser superior a 100.');
    }

    /** @test */
    public function commission_field_must_be_a_number()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service                  = create(Service::class, ['team_id' => $this->team()]);
        $attributes               = $service->toArray();
        $attributes['commission'] = 'not a number';

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertJsonHasFragmentError('commission', 'O campo comissão deve ser um número.');
    }

    /** @test */
    public function price_field_is_required()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service             = create(Service::class, ['team_id' => $this->team()]);
        $attributes          = $service->toArray();
        $attributes['price'] = '';

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertJsonHasFragmentError('price', 'O campo preço é obrigatório.');
    }

    /** @test */
    public function price_field_has_min_of_0()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service             = create(Service::class, ['team_id' => $this->team()]);
        $attributes          = $service->toArray();
        $attributes['price'] = -1;

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertJsonHasFragmentError('price', 'O campo preço deve ser pelo menos 0.');
    }

    /** @test */
    public function price_field_must_be_a_number()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service             = create(Service::class, ['team_id' => $this->team()]);
        $attributes          = $service->toArray();
        $attributes['price'] = 'not a number';

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertJsonHasFragmentError('price', 'O campo preço deve ser um número.');
    }

    /** @test */
    public function description_is_optional()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service                   = create(Service::class, ['team_id' => $this->team()]);
        $attributes                = $service->toArray();
        $attributes['description'] = '';

        $this->actingAs($user)
            ->updateService($service->id, $attributes)
            ->assertSuccessful();
    }
}
