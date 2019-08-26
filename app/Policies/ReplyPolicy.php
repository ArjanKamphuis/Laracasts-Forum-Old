<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the reply.
     *
     * @param  \App\User  $user
     * @param  \App\Reply $reply
     * @return bool
     */
    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }

    /**
     * Determine whether the user just published a reply.
     * 
     * @param  \App\User $user
     * @return bool
     */
    public function create(User $user) {
        $lastReply = $user->fresh()->lastReply;
        return $lastReply ? !$lastReply->wasJustPublished() : true;
    }
}
