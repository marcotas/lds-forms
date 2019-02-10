<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestResponse;

class UpdateClientTest extends TestCase
{
    use DatabaseTransactions;

    public function updateClient(Client $client, Team $team): TestResponse
    {
        return $this->putJson(route('clients.update', ['team' => $team->id, 'client' => $client->id]), $client->toArray());
    }

    /** @test */
    public function it_should_update_a_client_in_database_in_the_team_given_in_params()
    {
        $client = create(Client::class, ['team_id' => $this->team()]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->updateClient($client, $this->team())
            ->assertSuccessful();

        $this->assertDatabaseHas($client->getTable(), [
            'id'      => $client->id,
            'name'    => $client->name,
            'team_id' => $this->team()->id,
        ]);
    }

    /** @test */
    public function it_returns_404_if_the_user_doenst_belongs_to_the_team_of_the_client()
    {
        $client = create(Client::class);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->updateClient($client, $client->team)
            ->assertNotFound();
    }

    /** @test */
    public function name_is_required()
    {
        $client = create(Client::class, ['team_id' => $this->team()]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');
        $client->name = null;

        $this->actingAs($user)
            ->updateClient($client, $this->team())
            ->assertUnprocessableEntity()
            ->assertJsonHasFragmentError('name', 'O campo nome é obrigatório.');
    }

    /** @test */
    public function name_has_max_of_64_caracters()
    {
        $client = create(Client::class, ['name' => str_random(65), 'team_id' => $this->team()]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->updateClient($client, $this->team())
            ->assertUnprocessableEntity()
            ->assertJsonHasFragmentError('name', 'O campo nome não pode ser superior a 64 caracteres.');
    }

    /** @test */
    public function phone_is_optional()
    {
        $client = create(Client::class, ['team_id' => $this->team()]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');
        $client->phone = null;

        $this->actingAs($user)
            ->updateClient($client, $this->team())
            ->assertSuccessful()
            ->assertJsonFragment(['phone' => null]);

        $this->assertDatabaseHas($client->getTable(), [
            'id'    => $client->id,
            'phone' => null,
        ]);
    }

    /** @test */
    public function email_is_optional()
    {
        $client = create(Client::class, ['team_id' => $this->team()->id]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');
        $client->email = null;

        $this->actingAs($user)
            ->updateClient($client, $this->team())
            ->assertSuccessful()
            ->assertJsonFragment(['email' => null]);

        $this->assertDatabaseHas($client->getTable(), $client->only('id', 'email'));
    }

    /** @test */
    public function email_is_unique_in_a_team_and_when_present()
    {
        create(Client::class, ['email' => 'client@mail.com', 'team_id' => $this->team()->id]);
        $client = create(Client::class, ['team_id' => $this->team()->id]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');
        $client->email = 'client@mail.com';

        $this->actingAs($user)
            ->updateClient($client, $this->team())
            ->assertUnprocessableEntity()
            ->assertJsonHasFragmentError('email', 'O campo e-mail já está sendo utilizado.');
    }

    /** @test */
    public function email_ignores_when_its_being_updated()
    {
        $client = create(Client::class, ['team_id' => $this->team()->id]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');

        $this->actingAs($user)
            ->updateClient($client, $this->team())
            ->assertSuccessful();
    }

    /** @test */
    public function birthday_is_optional()
    {
        $client = create(Client::class, ['team_id' => $this->team()]);
        $user   = $this->teamMember();
        $user->givePermissionTo('manage.clients');
        $client->birthday = null;

        $this->actingAs($user)
            ->updateClient($client, $this->team())
            ->assertSuccessful()
            ->assertJsonFragment(['birthday' => null]);
    }
}
