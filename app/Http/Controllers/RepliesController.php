<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @param  $channelId
     * @param  App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread) {
        $this->validate(request(), ['body' => 'required']);
        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        return back()->with('flash', 'Your reply has been left.');
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
