<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

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
        return $reply->favorite();
    }
}
