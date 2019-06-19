<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Spam;
use App\Thread;

use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * @param  $channelId
     * @param  App\Thread $thread
     */
    public function index($channelId, Thread $thread) {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param  integer $channelId
     * @param  App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, Spam $spam) {
        $this->validate(request(), ['body' => 'required']);
        $spam->detect(request('body'));

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        return request()->expectsJson() ? $reply->load('owner') : back()->with('flash', 'Your reply has been left.');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(request(['body']));
    }

    /**
     * @param  App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply) {
        $this->authorize('update', $reply);
        $reply->delete();
        return request()->expectsJson() ? response(['status' => 'Reply deleted']) : back();
    }
}
