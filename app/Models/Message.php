<?php

namespace App\Models;

use App\MessageFiles;
use Cmgmyr\Messenger\Models\Message as BaseMessage;

class Message extends BaseMessage
{
    /**
     * Returns unread messages given the userId.
     *
     * @param Builder $query
     * @param int $userId
     * @param null $thread_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getUnreadForUserForThread(Builder $query, $userId, $thread_id = null)
    {
        $query = Participant::where('user_id', '!=', $userId)
            ->where('thread_id', $thread_id)
            ->whereHas('participants', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where(function (Builder $q) {
                        $q->where('last_read', '<', $this->getConnection()->raw($this->getConnection()->getTablePrefix() . $this->getTable() . '.created_at'))
                            ->orWhereNull('last_read');
                    });
            });

        if ($thread_id) {
            return $query->where('thread_id', '=', $thread_id);
        } else {
            return $query;
        }
    }

    function files()
    {
        return $this->hasMany(MessageFiles::class);
    }
}