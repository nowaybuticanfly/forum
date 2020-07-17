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

        $this->withoutExceptionHandling();
        $user = $this->signIn();

        $thread = factory('App\Thread')->make();


        $this->post('/threads', $thread->toArray());

        $this->get('/threads/' . $thread->id)
            ->assertSee($thread->body)
            ->assertSee($thread->title);
    }

}
