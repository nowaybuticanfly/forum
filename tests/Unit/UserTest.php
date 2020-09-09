<?php

namespace Tests\Unit;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;



    public function test_it_can_fetch_its_reply()
    {
        $user = factory('App\User')->create();

        $reply = factory('App\Reply')->create([
            'user_id' => $user->id,
        ]);


        $this->assertEquals($reply->id, $user->lastReply->id);

    }

    function test_it_can_determine_its_avatar_path()
    {
        $user = factory('App\User')->create();

        $this->assertEquals(asset('/storage/avatars/default.jpeg'), $user->avatar());


        $user->avatar_path = 'avatars/avatar.jpeg';

        $this->assertEquals(asset('/storage/avatars/avatar.jpeg'), $user->avatar());

    }





}
