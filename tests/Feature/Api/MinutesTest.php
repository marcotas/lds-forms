<?php

namespace Tests\Feature\Api;

use App\Models\Minute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class MinutesTest extends TestCase
{
    /** @test */
    public function index_should_return_a_list_of_minutes()
    {
        // Arrange
        $user   = $this->user();
        $minute = factory(Minute::class)->create();

        // Act
        $response = $this->actingAs($user, 'api')->get(route('api.minutes.index'));

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    $minute->toArray()
                ],
                'meta' => [
                    'total' => 1
                ],
                'links' => []
            ]);
    }

    /** @test */
    public function index_returns_401_when_not_authenticated()
    {
        // Act
        $response = $this->json('get', route('api.minutes.index'));

        // Assert
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function show_should_return_only_one_Minute()
    {
        // Arrange
        $minute = factory(Minute::class)->create();

        // Act
        $response = $this
            ->actingAs($this->user(), 'api')
            ->json('get', route('api.minutes.show', ['id' => $minute->id]));

        // Assert
        $response
            ->assertOk()
            ->assertJson([
                'data' => $minute->toArray(),
            ]);
    }

    /** @test */
    public function create_should_insert_one_minute_to_the_database()
    {
        // Arrange
        $minute = factory(Minute::class)->make([
            'first_prayer' => 'Marco Túlio'
        ]);

        // Act
        $this->assertDatabaseMissing('minutes', $minute->toArray());
        $this->assertEquals(0, Minute::count());
        $response = $this
            ->actingAs($this->user(), 'api')
            ->json('post', route('api.minutes.store'), $minute->toArray());

        // Assert
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => $minute->toArray()
            ]);
        $this->assertEquals(1, Minute::count());
    }

    /** @test */
    public function update_should_update_the_minute_in_database()
    {
        // Arrange
        $params = [
            'first_prayer' => 'Marco Túlio',
            'announcement' => 'Atividade hoje',
        ];
        $minute = factory(Minute::class)->create();

        // Act
        $response = $this
            ->actingAs($this->user(), 'api')
            ->json('put', route('api.minutes.update', ['id' => $minute->id]), $params);

        // Assert
        $response->assertOk()
            ->assertJson([
                'data' => array_merge($params, [
                    'id' => $minute->id,
                ])
            ]);
    }
}
