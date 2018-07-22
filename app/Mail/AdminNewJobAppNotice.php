<?php

namespace App\Mail;

use App\Models\Jobs\Application;
use App\Models\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class AdminNewJobAppNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $applicant;

    /**
     * Create a new message instance.
     *
     * @param Job $job
     * @param Application $applicant
     */
    public function __construct(Job $job, Application $applicant)
    {
        $this->job = $job;
        $this->applicant = $applicant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.admin-new-job-notice')
            ->subject('I have applied for your job. Please review.')
            ->from(Auth::user()->email,Auth::user()->name);
    }
}
