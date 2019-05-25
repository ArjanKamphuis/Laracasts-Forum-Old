<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;
    
    protected $guarded = [];
    protected $with = ['owner', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited'];

    protected static function boot() {
        parent::boot();

        static::created(function (Reply $reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function (Reply $reply) {
            $reply->thread->decrement('replies_count');
        });
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread() {
        return $this->belongsTo(Thread::class);
    }

    public function path() {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function getFavoritesCountAttribute() {
        return $this->favorites->count();
    }
}
