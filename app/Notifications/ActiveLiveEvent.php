<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ActiveLiveEvent extends Notification
{
    use Queueable;

    public $user, $event;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $event)
    {
        $this->user = $user;
        $this->event = $event;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {   
        if($this->user->id == $notifiable->id) {
            return [
                'name' => 'Hello ' . $this->user->name,
                'message' => ' watch your live event ' . $this->event->title,
                'profile' => route('event.live', $this->event->youtube_event_id) 
            ];
        }

        return [
            'name' => $this->user->name,
            'message' => 'started live event ' . $this->event->title,
            'profile' => route('event.live', $this->event->youtube_event_id) 
        ];
    }
}
