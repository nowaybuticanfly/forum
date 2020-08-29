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
        $john = factory('App\User')->create([
            'name' => 'JohnDoe'
        ]);

        $alice = factory('App\User')->create([
            'name' => 'AliceDoe'
        ]);

        $reply = factory('App\Reply')->create([
            'body' => '@JohnDoe and  @AliceDoe look here'
        ]);

        $this->assertEquals(['JohnDoe', 'AliceDoe'], $reply->mentionedUsers());


    }


}
