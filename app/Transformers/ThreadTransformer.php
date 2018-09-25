<?php

namespace App\Transformers;

use Illuminate\Support\Facades\Auth;

class ThreadTransformer extends Transformer
{
    /**
     * @param $thread
     * @return array
     */
    public function transform($thread)
    {
        return [
            "id" => $thread->id,
            "thread_type" => $thread->thread_type,
            "subject" => $thread->title(),
            "description" => $thread->description,
            "avatar" =>  $thread->avatar(),
            "unread_count" => $thread->userUnreadMessagesCount(Auth::id()),
            "created_at" => $thread->created_at
        ];
    }
}