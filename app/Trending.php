<?php

namespace App;


use Illuminate\Support\Facades\Redis;

class Trending {

    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cashKey(), 0, 4));
    }

    public function push($thread)
    {
        Redis::zincrby($this->cashKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function cashKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' :'trending_threads';
    }

    public function reset()
    {
        Redis::del($this->cashKey());
    }

}
