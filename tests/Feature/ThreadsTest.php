<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp() : void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    public function test_a_user_can_browse_threads()
    {

        $response = $this->get('/threads');

        $response->assertSee($this->thread->title);

    }


    public function test_a_user_can_see_a_single_thread()
    {

        $response = $this->get('/threads/' . $this->thread->id);

        $response->assertSee($this->thread->title);


        $response->assertSee($this->thread->body);
    }


    public function test_a_user_can_see_replies_that_are_associated_with_thread()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);

        $response = $this->get('/threads/' . $this->thread->id)
            ->assertSee($reply->body);
    }

}
