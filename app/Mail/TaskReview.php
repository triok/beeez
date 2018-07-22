<?php

namespace App\Mail;

use App\Models\Jobs\Job;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskReview extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Job $job */
    public $job;

    /** @var User $user */
    public $user;
    /** @var array|Request|string $request */
    public $request;

    public function __construct(Job $job, User $user)
    {
        $this->job     = $job;
        $this->user    = $user;
        $this->request = request();
    }

    public function build()
    {
        $mail =  $this->markdown('emails.tasks.review')
            ->subject('The user submitted an application for review')
            ->from($this->user->email, $this->user->name);

        if ($this->request->exists('files')) {
            foreach ($this->request->file('files') as $file) {
                $path = $file->store('/public/files/emails');
                $mail->attach(storage_path('app/' . $path));
            }
        }

        return $mail;
    }
}
