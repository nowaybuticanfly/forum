<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{

    use RefreshDatabase;


    public function test_a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create();


        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->subscriptions);

    }


    public function test_a_user_can_unsubscribe_from_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = factory('App\Thread')->create();

        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }

}

