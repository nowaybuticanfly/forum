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

    public function test_a_user_can_filter_threads_by_only_those_that_are_unanswered()
    {
        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->create(['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();


        $this->assertCount(1, $response['data']);
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

        $this->assertEquals([3,2,0], array_column($response['data'], 'replies_count'));


    }


    public function test_anyone_can_request_all_replies_for_a_given_thread()
    {
        $thread = factory('App\Thread')->create();

        factory('App\Reply', 2)->create(['thread_id' => $thread->id]);

        $response = $this->getJson($thread->path() . '/replies')->json();


        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);

    }


}
