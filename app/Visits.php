<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits {
    protected $item;

    public function __construct($item) {
        $this->item = $item;
    }
    
    public function reset() {
        Redis::del($this->cacheKey());
        return $this;
    }

    public function record() {
        Redis::incr($this->cacheKey());
        return $this;
    }

    public function count() {
        return Redis::get($this->cacheKey()) ?: 0;
    }

    public function cacheKey() {
        return "threads.{$this->item->id}.visits";
    }
}
