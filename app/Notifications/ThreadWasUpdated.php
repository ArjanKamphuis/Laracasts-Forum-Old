<?php

namespace App\Notifications;

use App\Reply;
use App\Thread;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThreadWasUpdated extends Notification
{
    use Queueable;
    
    protected $thread, $reply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Thread $thread, Reply $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->reply->owner->name} replied to {$this->thread->title}",
            'link' => $this->reply->path()
        ];
    }
}
