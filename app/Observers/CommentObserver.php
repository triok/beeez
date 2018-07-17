<?php

namespace App\Observers;

use App\Events\CommentAddedEvent;
use App\Mail\NewCommentMail;
use App\Mail\NewCommentNotice;
use App\Models\Comment;
use App\Models\Jobs\Job;
use Illuminate\Support\Facades\Mail;

class CommentObserver
{
    public function creating(Comment $comment)
    {
        /** @var Job $job */
        $job = Job::query()->find(request()->job);

        Mail::to($job->user->email)->send(new NewCommentMail($comment, $job));
    }
}