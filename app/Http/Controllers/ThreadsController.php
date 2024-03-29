<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;
use App\Trending;
use App\Filters\ThreadFilters;
use App\Rules\Recaptcha;
use App\Rules\SpamFree;

class ThreadsController extends Controller
{
    /**
     * Create a new ThreadsController instance.
     */
    public function  __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Channel $channel
     * @param  \App\Filters\ThreadFilter $filters
     * @param  \App\Trending $trending
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);
        return request()->wantsJson() ? $threads : view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Rules\Recaptcha $recaptcha
     * @return \Illuminate\Http\Response
     */
    public function store(Recaptcha $recaptcha)
    {
        request()->validate([
            'title' => ['required', new SpamFree],
            'body'  => ['required', new SpamFree],
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required', $recaptcha]
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);
        return request()->wantsJson() 
            ? response($thread, 201)
            : redirect($thread->path())->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $channel
     * @param  \App\Thread $thread
     * @param  \App\Trending $trending
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);
        $thread->increment('visits');

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  integer $channel
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        $thread->update(request()->validate([
            'title' => ['required', new SpamFree],
            'body'  => ['required', new SpamFree]
        ]));
        return $thread;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $channel
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        $thread->delete();
        return request()->wantsJson() ? response([], 204) : redirect('/threads');
    }

    /**
     * Get The threads filtered and by channel if exists
     *
     * @param  \App\Channel $channel
     * @param  \App\Filters\ThreadFilter $filters
     * @return \Illuminate\Http\Response
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters) {
        $threads = Thread::filter($filters)->latest();
        return ($channel->exists ? $threads->where('channel_id', $channel->id) : $threads)
            ->paginate(25); 
    }
}
