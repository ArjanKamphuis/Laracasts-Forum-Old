<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_threads() {
        $this->signIn();
        $thread = create('App\Thread');

        $this->post("{$thread->path()}/subscriptions");
        $this->assertCount(0, auth()->user()->notifications);
    }

    /** @test */
    public function a_user_can_unsubscribe_to_threads() {
        $this->signIn();
        $thread = create('App\Thread');
        $thread->subscribe();

        $this->delete("{$thread->path()}/subscriptions");
        $this->assertCount(0, $thread->subscriptions);
    }
}