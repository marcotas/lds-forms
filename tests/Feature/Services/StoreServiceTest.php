<?php

namespace Tests\Feature\Services;

use App\Models\Service;
use Tests\TestCase;
use Tests\TestResponse;

class StoreServiceTest extends TestCase
{
    public function storeService(array $service): TestResponse
    {
        return $this->postJson(route('services.store'), $service);
    }

    /** @test */
    public function it_should_store_in_database()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = make(Service::class);

        $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertSuccessful();

        $this->assertDatabaseHas($service->getTable(), [
            'name'        => $service->name,
            'price'       => $service->price,
            'commission'  => $service->commission,
            'description' => $service->description,
            'team_id'     => $this->team()->id,
        ]);
    }

    /** @test */
    public function it_stores_in_the_team_of_the_current_user()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $anotherTeam = $this->createTeam();
        $service     = make(Service::class, ['team_id' => $anotherTeam]);

        $service = $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertSuccessful()
            ->original;

        $this->assertEquals($service->team_id, $this->team()->id);
        $this->assertNotEquals($service->team_id, $anotherTeam->id);

        $this->assertDatabaseHas($service->getTable(), [
            'name'        => $service->name,
            'price'       => $service->price,
            'commission'  => $service->commission,
            'description' => $service->description,
            'team_id'     => $this->team()->id,
        ]);
    }

    /** @test */
    public function it_must_be_permitted()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('another thing');
        $service = make(Service::class);

        $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertForbidden();
    }

    /** @test */
    public function name_field_is_required()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = make(Service::class, ['name' => '']);

        $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertJsonHasFragmentError('name', 'O campo nome é obrigatório.');
    }

    /** @test */
    public function name_field_is_unique_in_team()
    {
        $name = 'Existing service name';
        create(Service::class, ['team_id' => $this->team(), 'name' => $name]);

        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = make(Service::class, ['name' => $name]);

        $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertJsonHasFragmentError('name', 'O campo nome já está sendo utilizado.');
    }

    /** @test */
    public function name_field_hax_max_of_255_caracters()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = make(Service::class, ['name' => str_random(256)]);

        $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertJsonHasFragmentError('name', 'O campo nome não pode ser superior a 255 caracteres.');
    }

    /** @test */
    public function name_can_be_duplicated_between_teams()
    {
        $name        = 'Existing service name';
        $anotherTeam = $this->createTeam();
        create(Service::class, ['team_id' => $anotherTeam, 'name' => $name]);

        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = make(Service::class, ['name' => $name]);

        $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertSuccessful();
    }

    /** @test */
    public function commission_field_is_required()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = make(Service::class, ['commission' => null]);

        $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertJsonHasFragmentError('commission', 'O campo comissão é obrigatório.');
    }

    /** @test */
    public function commission_field_has_min_of_0()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = make(Service::class, ['commission' => -1]);

        $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertJsonHasFragmentError('commission', 'O campo comissão deve ser pelo menos 0.');
    }

    /** @test */
    public function commission_field_has_max_of_100()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service = make(Service::class, ['commission' => 101]);

        $this->actingAs($user)
            ->storeService($service->toArray())
            ->assertJsonHasFragmentError('commission', 'O campo comissão não pode ser superior a 100.');
    }

    /** @test */
    public function commission_field_must_be_a_number()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service               = make(Service::class)->toArray();
        $service['commission'] = 'not a number';

        $this->actingAs($user)
            ->storeService($service)
            ->assertJsonHasFragmentError('commission', 'O campo comissão deve ser um número.');
    }

    /** @test */
    public function price_field_is_required()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service          = make(Service::class)->toArray();
        $service['price'] = '';

        $this->actingAs($user)
            ->storeService($service)
            ->assertJsonHasFragmentError('price', 'O campo preço é obrigatório.');
    }

    /** @test */
    public function price_field_has_min_of_0()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service          = make(Service::class)->toArray();
        $service['price'] = -1;

        $this->actingAs($user)
            ->storeService($service)
            ->assertJsonHasFragmentError('price', 'O campo preço deve ser pelo menos 0.');
    }

    /** @test */
    public function price_field_must_be_a_number()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service          = make(Service::class)->toArray();
        $service['price'] = 'not a number';

        $this->actingAs($user)
            ->storeService($service)
            ->assertJsonHasFragmentError('price', 'O campo preço deve ser um número.');
    }

    /** @test */
    public function description_field_is_optional()
    {
        $user = $this->teamMember();
        $user->givePermissionTo('manage.services');
        $service                = make(Service::class)->toArray();
        $service['description'] = '';

        $this->actingAs($user)
            ->storeService($service)
            ->assertSuccessful();
    }
}
