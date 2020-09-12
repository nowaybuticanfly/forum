<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;


    public function setUp() : void
    {
        parent::setUp();

        Redis::del('trending_threads');
    }


    public function test_it_increments_a_thread_score_each_time_thread_is_read()
    {
        $this->assertEmpty(Redis::zrevrange('trending_threads', 0, -1));

        $thread = factory('App\Thread')->create();




        $this->call('GET', $thread->path());

        $trending = Redis::zrevrange('trending_threads', 0, -1);

        $this->assertCount(1, $trending);
    }



}
