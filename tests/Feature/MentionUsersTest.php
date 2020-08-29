<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;



class MentionUsersTest extends TestCase
{
    use RefreshDatabase;



    public function test_mentioned_in_a_reply_users_are_notified()
    {
        $john = factory('App\User')->create(['name' => 'JohnDoe']);

        $this->signIn($john);

        $jane = factory('App\User')->create(['name' => 'JaneDoe']);

        $thread = factory('App\Thread')->create();


        $reply = factory('App\Reply')->make([
           'body' => '@JaneDoe look at this'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);





    }

}
