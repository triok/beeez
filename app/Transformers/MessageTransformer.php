<?php

namespace App\Transformers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageTransformer extends Transformer
{
    /**
     * @param $message
     * @return array
     */
    public function transform($message)
    {
        $user = $message->user;

        return [
            "id" => $message->id,
            "user" => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->getStorageDir() . $user->avatar,
            ],
            "body" => $message->body,
            "files" => $message->files,
            "created_at" => $message->created_at,
            "created_at_human" => $message->created_at->diffForHumans(),
        ];
    }
}