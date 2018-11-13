<?php

namespace App\Notifications;

use App\Models\Cv;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CvApprovedNotification extends Notification
{
    use Queueable;

    protected $request;

    protected $cv;

    /**
     * Create a new notification instance.
     *
     * @param Request $request
     * @param Cv $cv
     */
    public function __construct(Request $request, Cv $cv)
    {
        $this->request = $request;

        $this->cv = $cv;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
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
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'cv_id' => $this->cv->id,
            'request' => $this->request->all(),
        ];
    }
}
