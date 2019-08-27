<?php

namespace App\Http\Controllers;

use Exception;
use App\Reply;
use App\Thread;
use App\Http\Requests\CreatePostRequest;
use App\Rules\SpamFree;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct() {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * @param  int $channelId
     * @param  App\Thread $thread
     */
    public function index($channelId, Thread $thread) {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param  integer $channelId
     * @param  App\Thread $thread
     * @param  App\Http\Forms\CreatePostForm $form
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form) {
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }

    /**
     * Update and existing reply
     * 
     * @param  App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $this->validate(request(), ['body' => ['required', new SpamFree]]);
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
