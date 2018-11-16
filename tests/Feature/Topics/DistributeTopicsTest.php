<?php

namespace Tests\Feature\Topics;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Topic;
use App\Actions\Topics\DistributeTopics;

class DistributeTopicsTest extends TestCase
{
    /** @test */
    public function it_should_distribute_topics_without_date_to_next_sundays()
    {
        // Arrange
        $topics = factory(Topic::class, 6)->create(['date' => null]);
        // Act
        $newTopics = DistributeTopics::run([
            'topics' => $topics->toArray(),
        ]);

        // Assert
    }
}
