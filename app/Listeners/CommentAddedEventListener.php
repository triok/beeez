<?php

namespace App\Listeners;

use App\Events\CommentAddedEvent;
use App\Mail\NewCommentNotice;
use App\Models\Jobs\Job;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CommentAddedEventListener
{

    public function handle(CommentAddedEvent $event)
    {
        /** @var Job $job */
        $job = Job::query()->find($event->getJob());

        Mail::to($job->user->email)->send(new NewCommentNotice($event->getComment(), $job));
    }
}
