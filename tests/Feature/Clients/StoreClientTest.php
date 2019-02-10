<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestResponse;

class StoreClientTest extends TestCase
{
    use DatabaseTransactions;

    public function storeClient($params, Team $team): TestResponse
    {
        return $this->postJson(route('clients.store', $team), $params);
    }

    /** @test */
    public function it_should_store_a_client_in_database_in_the_team_given_in_params()
    {
        $client = make(Client::class);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->storeClient($client->toArray(), $this->team())
            ->assertSuccessful();

        $this->assertDatabaseHas($client->getTable(), [
            'name'    => $client->name,
            'team_id' => $this->team()->id,
        ]);
    }

    /** @test */
    public function name_is_required()
    {
        $client = make(Client::class, ['name' => null]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->storeClient($client->toArray(), $this->team())
            ->assertUnprocessableEntity()
            ->assertJsonHasFragmentError('name', 'O campo nome é obrigatório.');
    }

    /** @test */
    public function name_has_max_of_64_caracters()
    {
        $client = make(Client::class, ['name' => str_random(65)]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->storeClient($client->toArray(), $this->team())
            ->assertUnprocessableEntity()
            ->assertJsonHasFragmentError('name', 'O campo nome não pode ser superior a 64 caracteres.');
    }

    /** @test */
    public function phone_is_optional()
    {
        $client = make(Client::class, ['phone' => null]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->storeClient($client->toArray(), $this->team())
            ->assertSuccessful()
            ->assertJsonFragment(['phone' => null]);
    }

    /** @test */
    public function email_is_optional()
    {
        create(Client::class, ['email' => null, 'team_id' => $this->team()->id]);
        $client = make(Client::class, ['email' => null, 'team_id' => $this->team()->id]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->storeClient($client->toArray(), $this->team())
            ->assertSuccessful()
            ->assertJsonFragment(['email' => null]);
    }

    /** @test */
    public function email_is_unique_in_a_team_and_when_present()
    {
        create(Client::class, ['email' => 'client@mail.com', 'team_id' => $this->team()->id]);
        $client = make(Client::class, ['email' => 'client@mail.com', 'team_id' => $this->team()->id]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->storeClient($client->toArray(), $this->team())
            ->assertUnprocessableEntity()
            ->assertJsonHasFragmentError('email', 'O campo e-mail já está sendo utilizado.');
    }

    /** @test */
    public function birthday_is_optional()
    {
        $client = make(Client::class, ['birthday' => null]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->storeClient($client->toArray(), $this->team())
            ->assertSuccessful()
            ->assertJsonFragment(['birthday' => null]);
    }
}
