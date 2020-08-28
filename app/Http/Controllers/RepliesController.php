<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Illuminate\Support\Facades\Gate;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    public function store($channelId, Thread $thread)
    {
        try {
            if (Gate::denies('create', new Reply)) {
                return response('You cant reply more than once per minute', 429);
            }

            \request()->validate([
                'body' => ['required', new SpamFree]
            ]);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response('Sorry your reply is invalid', 422);
        }

        return $reply->load('owner');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        if(\request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            \request()->validate([
                'body' => ['required', new SpamFree]
            ]);

            $reply->update(['body' => request('body')]);

        } catch (\Exception $e) {
            return response('Sorry your reply is invalid', 422);
        }
    }

}
