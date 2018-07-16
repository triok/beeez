<?php

namespace App\Observers;

use App\Events\CommentAddedEvent;
use App\Models\Comment;

class CommentObserver
{
    public function creating(Comment $comment)
    {
        event(new CommentAddedEvent($comment,request()->job));
    }
}