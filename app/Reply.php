<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;
    
    protected $guarded = [];
    protected $with = ['owner', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

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

    public function mentionedUsers() {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);
        return $matches[1];
    }

    public function wasJustPublished() {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function path() {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function getFavoritesCountAttribute() {
        return $this->favorites->count();
    }

    public function setBodyAttribute($body) {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    public function isBest() {
        return $this->id == $this->thread->best_reply_id;
    }

    public function getIsBestAttribute() {
        return $this->isBest();
    }
}
