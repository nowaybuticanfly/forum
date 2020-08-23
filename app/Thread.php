<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Activity;
use App\Notifications\ThreadWasUpdated;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['creator', 'channel'];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function($thread) {
            $thread->replies->each->delete();
        });

    }

    public function path()
    {
        return '/threads/' . $this->channel->slug . '/' . $this->id;
    }


    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function replies()
    {
        return $this->hasMany('App\Reply')
            ->withCount('favorites')
            ->with('owner');
    }


    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        //prepare  notifications

        $this->subscriptions
            ->filter(function ($sub) use ($reply) {
                return $sub->user_id != $reply->user_id;
            })
            ->each->notify($reply);


        return $reply;
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }


    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
           'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unSubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany('App\ThreadSubscriptions');
    }


    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where(['user_id' => auth()->id()])
            ->exists();
    }

}
