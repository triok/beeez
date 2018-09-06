<?php

namespace App\Models;

use App\Models\Participant;
use App\User;
use Cmgmyr\Messenger\Models\Thread as BaseThread;

class Thread extends BaseThread
{
    public function participant()
    {
        $participant = Participant::where('thread_id', $this->id)
            ->where('user_id', '!=', auth()->user()->id)
            ->first();

        return User::find($participant->user_id);
    }
}
