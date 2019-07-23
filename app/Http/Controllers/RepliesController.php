<?php

namespace App\Http\Controllers;

use Exception;
use App\Reply;
use App\Thread;
use App\Rules\SpamFree;

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
        try {
            $this->validate(request(), ['body' => ['required', new SpamFree]]);
            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
        return $reply->load('owner');
    }

    /**
     * Update and existing reply
     * 
     * @param  App\Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        try {
            $this->validate(request(), ['body' => 'required', new SpamFree]);
            $reply->update(request(['body']));
        } catch (Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
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
