<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create('App\User', ['name' => 'JohnDoe']);
        $jane = create('App\User', ['name' => 'JaneDoe']);
        $this->signIn($john);

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => '@JaneDoe look at this. Also @FrankDoe.']);

        $this->postJson($thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_characters() {
        create('App\User', ['name' => 'JohnDoe']);
        create('App\User', ['name' => 'JohnDeo']);
        create('App\User', ['name' => 'JaneDoe']);

        $results = $this->json('GET', '/api/users', ['name' => 'john']);
        $this->assertCount(2, $results->json());
    }
}
