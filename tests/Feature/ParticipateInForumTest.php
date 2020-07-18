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

        $user = $this->signIn();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();


        $this->post($thread->path() . '/replies', $reply->toArray());



        $this->get($thread->path())
            ->assertSee($reply->body);

    }


    public function test_a_reply_requires_body()
    {
        $user = $this->signIn();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make(['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');

    }




}
