<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_unauthenticated_user_can_not_create_a_thread()
    {

        $this->withoutExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->make();


        $this->post('/threads', $thread->toArray());


    }


    public function test_an_authenticated_user_can_create_a_thread()
    {

        $user = $this->signIn();

        $thread = factory('App\Thread')->make();

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->body)
            ->assertSee($thread->title);
    }


    public function test_unauthorized_users_cannot_delete_threads()
    {

        $thread = factory('App\Thread')->create();

        $this->delete($thread->path())
            ->assertRedirect('/login');


        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);

    }


    public function test_authorized_users_can_delete_a_thread()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);
        $reply = factory('App\Reply')->create(['thread_id' => $thread->id]);

        $this->json('DELETE', $thread->path());

        $this->assertDatabaseMissing('threads', ['id' => $thread->id])
            ->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }


    public function publishThread($overrides=[])
    {
        $this->signIn();

        $thread = factory('App\Thread')->make($overrides);

        return $this->post('/threads', $thread->toArray());
    }

    public function test_a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function test_a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function test_a_thread_requires_valid_channel()
    {

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        // check if provided channel doesnt exist
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => 9999])
            ->assertSessionHasErrors('channel_id');
    }

}
