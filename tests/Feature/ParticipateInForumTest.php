<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_unauthenticated_user_may_not_reply_to_a_thread()
    {

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();


        $this->post('threads/'.$thread->id.'/replies', $reply->toArray());

        $this->assertEmpty($thread->replies);
    }


    public function test_an_authenticated_user_may_reply_to_a_thread()
    {

        $this->signIn();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make()->toArray();


        $this->post($thread->path() . '/replies', $reply);


        $this->assertDatabaseHas('replies', ['body' => $reply['body'], 'thread_id' => $thread->id]);

    }


    public function test_a_reply_requires_body()
    {
        $user = $this->signIn();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make(['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');

    }

    public function test_unauthorized_users_can_not_delete_a_reply()
    {
        $reply = factory('App\Reply')->create();

        $this->delete('/replies/' . $reply->id)
            ->assertRedirect('login');

        $this->signIn();

        $this->delete('/replies/' . $reply->id)
            ->assertStatus(403);
    }

    public function test_authorized_user_can_delete_a_reply()
    {
        $this->signIn();

        $reply = factory('App\Reply')->create(['user_id' => auth()->id()]);

        $this->delete('/replies/' . $reply->id)
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies',[
            'id' => $reply->id,
        ]);

    }


    public function test_unauthorized_users_can_not_update_replies()
    {
        $reply = factory('App\Reply')->create();
        $updatedReply = 'Reply has been changed';

        $this->patch('/replies/' . $reply->id, ['body' => $updatedReply])
            ->assertRedirect('login');

        $this->signIn();

        $this->patch('/replies/' . $reply->id, ['body' => $updatedReply])
            ->assertStatus(403);
    }

    public function test_authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = factory('App\Reply')->create(['user_id' => auth()->id()]);

        $updatedReply = 'Reply has been changed';

        $this->patch('/replies/' . $reply->id, ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }

    public function test_replies_that_contain_spam_may_not_be_created()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make([
            'body' => 'Yahoo Customer Support'
        ])->toArray();

        $this->expectException(\Exception::class);

        $this->post($thread->path() . '/replies', $reply);
    }

}
