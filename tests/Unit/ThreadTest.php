<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ThreadTest extends TestCase
{
    use RefreshDatabase;
    protected $thread;

    public function setUp() : void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }


    public function test_it_has_an_owner()
    {

        $this->assertInstanceOf('App\User', $this->thread->creator);
    }


    public function test_it_has_replies()
    {


        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);

    }



    public function test_it_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);


        $this->assertCount(1, $this->thread->replies);
    }

    public function test_it_notifies_alL_the_subscribers_when_reply_is_added()
    {
        Notification::fake();
        $this->signIn()
            ->thread->subscribe()
            ->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);


        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);

    }


    public function test_it_belongs_to_a_channel()
    {
        $thread = factory('App\Thread')->create();

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    public function test_it_can_make_a_string_path()
    {
        $thread = factory('App\Thread')->create();

        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
    }



    public function test_it_can_be_subscribed_to()
    {
        $thread = factory('App\Thread')->create();


        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->count()
        );
    }

    public function test_it_can_be_unsibscribed_from()
    {
        $thread = factory('App\Thread')->create();


        $thread->subscribe($userId = 1);


        $thread->unsubscribe($userId);

        $this->assertEquals(
            0,
            $thread->subscriptions()->count()
        );
    }

    public function test_it_knows_if_it_is_subscribed_to()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);


    }


    public function test_a_thread_can_check_if_the_authenticated_user_has_read_all_the_replies()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create();


        tap(auth()->user(), function($user) use ($thread) {

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));
        });

    }

    public function test_it_records_each_visits()
    {
        $thread = factory('App\Thread')->make(['id' => 1]);

        $thread->resetVisits();

        $this->assertSame(0, $thread->visits());


        $thread->recordVisit();

        $this->assertEquals(1, $thread->visits());



    }

}
