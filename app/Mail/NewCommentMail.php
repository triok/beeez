<?php

namespace App\Mail;

use App\Models\Comment;
use App\Models\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Comment $comment */
    public $comment;
    /** @var Job $job */
    public $job;

    public function __construct(Comment $comment, Job $job)
    {
        $this->comment = $comment;
        $this->job     = $job;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.comments.new')
            ->subject('There is a new comment for your task')
            ->from(auth()->user()->email, auth()->user()->name);
    }
}
