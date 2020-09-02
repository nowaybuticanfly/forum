<?php

namespace Tests\Unit;


use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyTest extends TestCase
{

    use RefreshDatabase;


    public function test_it_has_an_owner()
    {
        $reply = factory('App\Reply')->create();

        $this->assertInstanceOf('App\User', $reply->owner);
    }


    public function test_it_knows_if_it_was_just_published()
    {
        $reply = factory('App\Reply')->create();

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();


        $this->assertFalse($reply->wasJustPublished());
    }


    public function test_it_can_detect_all_mentioned_users_in_its_body()
    {
        $reply = new \App\Reply([
            'body' => '@JohnDoe and  @AliceDoe look here'
        ]);

        $this->assertEquals(['JohnDoe', 'AliceDoe'], $reply->mentionedUsers());
    }


    public function test_it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $this->withoutExceptionHandling();

        $reply = new \App\Reply([
            'body' => '@JohnDoe look here'
        ]);

        $this->assertEquals('<a href="/profiles/JohnDoe">@JohnDoe</a> look here', $reply->body);

    }
}
