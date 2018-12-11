<?php

namespace App\Notifications;

use App\Models\Cv;
use App\Models\Jobs\Job;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JobOwnerReportNotification extends Notification
{
    use Queueable;

    protected $job;

    protected $report;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param Job $job
     * @param User $user
     * @param string $report
     */
    public function __construct(Job $job, User $user, $report = '')
    {
        $this->job = $job;

        $this->user = $user;

        $this->report = $report;
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
            'job_id' => $this->job->id,
            'user_id' => $this->user->id,
            'report' => $this->report
        ];
    }
}
