<?php

namespace App\Mail;

use App\Models\Jobs\Application;
use App\Models\Jobs\Job;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyUserAppStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $application;

    /**
     * Create a new message instance.
     *
     * @param Job $job
     * @param Application $application
     * @param User $applicant
     */
    public function __construct(Job $job, Application $application, User $applicant)
    {
        $this->job = $job;
        $this->application=$application;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user-app-status-notice')
            ->subject('You job application status has been updated')
            ->from(env('MAIL_FROM_ADDRESS', env('MAIL_FROM_NAME')));
    }
}
