<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Favorite;


class Reply extends Model
{
    use RecordsActivity;
    use Favorable;

    protected $guarded = [];
    protected $with = ['owner', 'favorites'];

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }



}
