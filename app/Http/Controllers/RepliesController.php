<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Inspections\Spam;

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
    public function store($channelId, Thread $thread) {
        $this->validateReply();
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        return request()->expectsJson() ? $reply->load('owner') : back()->with('flash', 'Your reply has been left.');
    }

    /**
     * Update and existing reply
     * 
     * @param  App\Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $this->validateReply();
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

    protected function validateReply() {
        $this->validate(request(), ['body' => 'required']);
        resolve(Spam::class)->detect(request('body'));
    }
}
