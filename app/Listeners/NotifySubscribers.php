<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        $reply = $event->reply;
        $thread = $reply->thread;
        $thread->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each->notify($reply);
    }
}
