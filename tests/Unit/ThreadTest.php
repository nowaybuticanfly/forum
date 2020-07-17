<?php

namespace Tests\Unit;

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

}
