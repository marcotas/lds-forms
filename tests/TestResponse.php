<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestResponse as BaseTestResponse;
use Illuminate\Http\Response;

class TestResponse extends BaseTestResponse
{
    public function assertJsonHasFragmentError(string $field, string $message) : self
    {
        $this->assertJsonValidationErrors([$field])
            ->assertJsonFragment([$message]);

        return $this;
    }

    public function assertUnprocessableEntity(): self
    {
        $this->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        return $this;
    }

    public function assertBadRequest(): self
    {
        $this->assertStatus(Response::HTTP_BAD_REQUEST);

        return $this;
    }
}
