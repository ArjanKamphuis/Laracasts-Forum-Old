<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;
    
    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function (Thread $thread) {
            $thread->replies->each->delete();
        });

        static::created(function (Thread $thread) {
            $thread->update(['slug' => $thread->title]);
        });
    }

    public function path() {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies() {
        return $this->hasMany(Reply::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Add a reply to the thread
     * 
     * @param  array $reply
     * @return Illuminate\Htpp\Response
     */
    public function addReply(array $reply) {
        $reply = $this->replies()->create($reply);
        event(new ThreadReceivedNewReply($reply));
        return $reply;
    }

    public function markBestReply(reply $reply) {
        $this->update(['best_reply_id' => $reply->id]);
    }

    public function scopeFilter($query, $filters) {
        return $filters->apply($query);
    }

    /**
     * Subscribe to a thread
     * 
     * @param  integer $userId
     * @return App\Thread
     */
    public function subscribe($userId = null) {
        $this->subscriptions()->create(['user_id' => $userId ?: auth()->id()]);
        return $this;
    }

    public function unsubscribe($userId = null) {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions() {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute() {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor(User $user) {
        return $this->updated_at > cache($user->visitedThreadCacheKey($this));
    }

    public function getRouteKeyName() {
        return 'slug';
    }

    /**
     * Set the proper slug value
     * 
     * @param string $value
     */
    public function setSlugAttribute($value) {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = "{$slug}-" . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }
}
