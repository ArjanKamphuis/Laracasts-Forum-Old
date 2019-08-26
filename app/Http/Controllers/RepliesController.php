<?php

namespace App\Http\Controllers;

use Exception;
use App\Reply;
use App\Thread;
use App\User;
use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;
use App\Rules\SpamFree;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     * @param  App\Http\Forms\CreatePostForm $form
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form) {
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');

        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);
        
        foreach ($matches[1] as $name) {
            if ($user = User::whereName($name)->first()) {
                $user->notify(new YouWereMentioned($reply));
            }
        }

        return $reply;
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
