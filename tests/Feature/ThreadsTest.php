<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Thread;

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

        $response = $this->get($this->thread->path());

        $response->assertSee($this->thread->title);


        $response->assertSee($this->thread->body);
    }


    public function test_a_user_can_see_replies_that_are_associated_with_thread()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);

        $response = $this->get($this->thread->path())
            ->assertSee($reply->body);
    }


    public function test_a_user_can_sort_threads_by_channel()
    {
        $channel = factory('App\Channel')->create();

        $threadInChannel = factory('App\Thread')->create(['channel_id' => $channel->id]);
        $threadNotInChannel = factory('App\Thread')->create();


        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    public function test_a_can_sort_threads_by_username()
    {
        $threadNotByJohn = factory('App\Thread')->create(['title' => 'notJohnDoe']);

        $this->signIn(factory('App\User')->create(['name' => 'JohnDoe']));

        $threadByJohn = factory('App\Thread')->create(['user_id' => auth()->id()]);


        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    public function test_a_user_can_sort_threads_by_popularity()
    {

        //If we create threads with 0, 3 and 2 replies respectively.

        $threadWithNoReplies = $this->thread;



        $threadWithThreeReplies = factory('App\Thread')->create();

        factory('App\Reply', 3)->create(['thread_id' => $threadWithThreeReplies->id]);




        $threadWithTwoReplies = factory('App\Thread')->create();

        factory('App\Reply', 2)->create(['thread_id' => $threadWithTwoReplies->id]);




        //When we filter them by popularity

        $response = $this->getJson('/threads?popular=1')->json();


        //Then they should be return from most replies to least

        $this->assertEquals([3,2,0], array_column($response, 'replies_count'));


    }
}
