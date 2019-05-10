<?php

namespace App\Http\Controllers;

use App\Reply;

class FavoritesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function store(Reply $reply) {
        $reply->favorite();
        return back();
    }

    /**
     * @param  \App\Reply $reply
     * @return -
     */
    public function destroy(Reply $reply) {
        $reply->unfavorite();
    }
}
