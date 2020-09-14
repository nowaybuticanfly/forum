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

    public function test_it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        factory('App\User')->create([
            'name'=>'JohnDoe'
        ]);

        factory('App\User')->create([
            'name'=>'JohnDoe2'
        ]);
        factory('App\User')->create([
            'name'=>'JaneDoe'
        ]);


        $results = $this->json('GET', 'api/users', ['name' => 'john']);

        $this->assertCount(2, $results->json());
    }

}
