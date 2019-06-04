<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  integer $channelId
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread)
    {
        $thread->subscribe();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $channelId
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channelId, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
