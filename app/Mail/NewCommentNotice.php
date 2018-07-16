<?php

namespace App\Mail;

use App\Models\Comment;
use App\Models\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCommentNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;
    public $job;

    /**
     * Create a new message instance.
     *
     * @param Comment $comment
     * @param Job $job
     */
    public function __construct(Comment $comment, Job $job)
    {
        $this->comment = $comment;
        $this->job = $job;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new-comment')
            ->subject('There is a new comment for your task')
            ->from(env('MAIL_FROM_ADDRESS', env('MAIL_FROM_NAME')));
    }


}
