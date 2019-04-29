<?php

namespace Tests\Feature\Auth;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use DatabaseTransactions;

    private function userData(array $attributes = [])
    {
        return [
            'team_name'             => $attributes['team_name'] ?? 'Cabeludos Team',
            'name'                  => $attributes['name'] ?? 'Cabeludos Owner',
            'email'                 => $attributes['email'] ?? 'owner@cabeludos.com',
            'password'              => $attributes['password'] ?? '123456',
            'password_confirmation' => $attributes['password_confirmation'] ?? '123456',
        ];
    }

    private function subscribe(array $attributes = []) : TestResponse
    {
        $attributes = $this->userData($attributes);

        return $this->postJson(route('subscribe'), $attributes);
    }

    /** @test */
    public function it_should_subscribe_a_guest_to_a_new_team_as_team_owner()
    {
        $this->withoutExceptionHandling();
        $response = $this->assertGuest()
            ->subscribe()
            ->assertSuccessful();

        $user = $response->original;
        $team = $user->current_team;
        $this->assertDatabaseHas('users', $user->only('id', 'name', 'email', 'password', 'current_team_id'));
        $this->assertDatabaseHas('teams', $team->only('id', 'name'));
        $this->assertTrue($user->current_team->name === $this->userData()['team_name']);
        $this->assertTrue($user->onTeam($team));
        $this->assertTrue($user->ownsTeam($team));
    }

    /** @test */
    public function it_should_login_the_subscribed_user_right_after_subscription()
    {
        $response = $this->assertGuest()
            ->subscribe()
            ->assertSuccessful();

        $user = $response->original;
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_should_allow_logged_user_to_subscribe_but_login_the_new_subscribed_user()
    {
        $response = $this->actingAs($this->teamMember())
            ->subscribe()
            ->assertSuccessful();

        $user = $response->original;
        $team = $user->current_team;
        $this->assertDatabaseHas('users', $user->only('id', 'name', 'email', 'password', 'current_team_id'));
        $this->assertDatabaseHas('teams', $team->only('id', 'name'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_should_validate_name()
    {
        $this->assertGuest()->subscribe(['name' => ''])
            ->assertJsonHasFragmentError('name', __('validation.required', ['attribute' => 'nome']));
        $this->assertGuest()->subscribe(['name' => str_random(256)])
            ->assertJsonHasFragmentError('name', 'O campo nome não pode ser superior a 255 caracteres.');
    }

    /** @test */
    public function it_should_validate_email()
    {
        $this->assertGuest()->subscribe(['email' => ''])
            ->assertJsonHasFragmentError('email', __('validation.required', ['attribute' => 'e-mail']));
        $this->assertGuest()->subscribe(['email' => str_random()])
            ->assertJsonHasFragmentError('email', __('validation.email', ['attribute' => 'e-mail']));
        $this->assertGuest()->subscribe(['email' => str_random(256)])
            ->assertJsonHasFragmentError('email', 'O campo e-mail não pode ser superior a 255 caracteres.');

        $user = create(User::class);
        $this->assertGuest()->subscribe(['email' => $user->email])
            ->assertJsonHasFragmentError('email', __('validation.unique', ['attribute' => 'e-mail']));
    }

    /** @test */
    public function it_should_validate_password()
    {
        $this->assertGuest()->subscribe(['password' => ''])
            ->assertJsonHasFragmentError('password', __('validation.required', ['attribute' => 'senha']));
        $this->assertGuest()->subscribe(['password' => str_random(5)])
            ->assertJsonHasFragmentError('password', 'O campo senha deve ter pelo menos 6 caracteres.');
        $this->assertGuest()->subscribe(['password' => str_random(6), 'password_confirmation' => ''])
            ->assertJsonHasFragmentError('password', __('validation.confirmed', ['attribute' => 'senha']));
    }

    /** @test */
    public function it_should_validate_team_name()
    {
        $this->assertGuest()->subscribe(['team_name' => ''])
            ->assertJsonHasFragmentError('team_name', __('validation.required', ['attribute' => 'nome da ala']));
        $this->assertGuest()->subscribe(['team_name' => str_random(256)])
            ->assertJsonHasFragmentError('team_name', 'O campo nome da ala não pode ser superior a 255 caracteres.');

        // Validates uniqueness for owner (the owner cannot be able to create a team with the same name of a team he already owns)
        $team = $this->createTeam();
        $this->assertGuest()->subscribe(['team_name' => $team->name, 'email' => $team->owner->email])
            ->assertJsonHasFragmentError('team_name', 'Esse usuário já é dono de uma ala com esse nome.');
    }

    /** @test */
    public function it_should_dispatch_the_registered_event_after_subscripion()
    {
        Event::fake();

        $this->assertGuest()
            ->subscribe()
            ->assertSuccessful();

        Event::assertDispatched(Registered::class);
    }

    /** @test */
    public function it_should_notify_the_user_with_a_verification_link_after_subscription()
    {
        Notification::fake();

        $response = $this->assertGuest()
            ->subscribe()
            ->assertSuccessful();
        $user = $response->original;

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
