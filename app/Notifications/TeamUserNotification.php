<?php

namespace App\Notifications;

use App\Models\TeamUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TeamUserNotification extends Notification
{
    use Queueable;

    protected $teamUser;

    /**
     * Create a new notification instance.
     *
     * @param TeamUsers $teamUser
     */
    public function __construct(TeamUsers $teamUser)
    {
        $this->teamUser = $teamUser;
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
        return [
            'team_id' => $this->teamUser->team_id,
            'position' => $this->teamUser->position,
        ];
    }
}
