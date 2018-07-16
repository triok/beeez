<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CommentAddedEvent
{
    use Dispatchable, SerializesModels;


    /** @var Comment $comment */
    private $comment;
    /** @var int $job */
    private $job;

    public function __construct(Comment $comment, int $job)
    {
        $this->comment = $comment;
        $this->job = $job;
    }

    /**
     * @return Comment
     */
    public function getComment(): Comment
    {
        return $this->comment;
    }

    /**
     * @return int
     */
    public function getJob(): int
    {
        return $this->job;
    }


}
