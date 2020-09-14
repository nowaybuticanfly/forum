<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();

    }

    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_current_user()
    {
        $thread = factory('App\Thread')->create()->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);


        $this->assertCount(0, auth()->user()->fresh()->notifications);


        $thread->addReply([
            'user_id' => factory('App\User')->create()->id,
            'body' => 'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);


    }

    function test_a_user_can_fetch_their_unread_notifications()
    {
        factory(DatabaseNotification::class)->create();

        $this->assertCount(
            1,
            $this->getJson('profiles/' . auth()->user()->name . '/notifications')->json()
        );

    }


    function test_a_user_can_mark_a_notification_as_read()
    {

        factory(DatabaseNotification::class)->create();

        $this->assertCount(1, auth()->user()->fresh()->unreadNotifications);

        $notificationId = auth()->user()->fresh()->unreadNotifications->first()->id;


        $this->delete('profiles/' . auth()->user()->name . '/notifications/' . $notificationId);

        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);

    }
}
