<?php

namespace Tests\Feature;

use App\Trending;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;


    private $trending;

    public function setUp() : void
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }


    public function test_it_increments_a_thread_score_each_time_thread_is_read()
    {
        $this->assertEmpty($this->trending->get());

        $thread = factory('App\Thread')->create();


        $this->call('GET', $thread->path());


        $this->assertCount(1, $this->trending->get());
    }



}
