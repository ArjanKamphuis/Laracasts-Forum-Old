<?php

namespace Tests\Feature;

use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies() {
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads() {
        $this->signIn();
        
        $thread = create('App\Thread');
        $reply = make('App\Reply');

        $this->post($thread->path() . '/replies', $reply->toArray());
        $this->get($thread->path());
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    public function a_reply_requires_a_body() {
        $this->signIn()->withExceptionHandling();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies() {
        $this->withExceptionHandling();
        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies() {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(302);
            
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies() {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        
        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_replies() {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $updatedReply = 'You been changed, fool.';

        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread() {
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id], 2);

        $response = $this->getJson("{$thread->path()}/replies")->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created() {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => 'Yahoo Customer Support']);

        $this->expectException(Exception::class);
        $this->post($thread->path() . '/replies', $reply->toArray());
    }
}