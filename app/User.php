<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'confirmed' => 'boolean'
    ];

    public function getRouteKeyName() {
        return 'name';
    }

    public function threads() {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity() {
        return $this->hasMany(Activity::class);
    }

    public function lastReply() {
        return $this->hasOne(Reply::class)->latest();
    }

    public function isAdmin() {
        return in_array($this->name, ['JohnDoe', 'JaneDoe']);
    }

    public function read(Thread $thread) {
        cache()->forever(
            $this->visitedThreadCacheKey($thread), 
            Carbon::now()
        );
    }

    public function visitedThreadCacheKey(Thread $thread) {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function getAvatarPathAttribute($avatar) {
        return asset($avatar ? 'storage/' . $avatar : 'images/avatars/default.png');
    }
}
