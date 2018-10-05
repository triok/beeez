<?php

namespace App\Models;

use App\User;
use Cmgmyr\Messenger\Models\Thread as BaseThread;
use Illuminate\Support\Facades\Storage;

class Thread extends BaseThread
{
    protected $fillable = ['user_id', 'team_id', 'subject', 'description', 'thread_type', 'avatar'];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function participant()
    {
        $participant = Participant::where('thread_id', $this->id)
            ->where('user_id', '!=', auth()->user()->id)
            ->first();

        return $participant ? User::find($participant->user_id) : null;
    }

    public function avatar()
    {
        if ($this->isSingleChat()) {
            if ($participant = $this->participant()) {
                return $participant->getStorageDir() . $participant->avatar;
            }
        }

        $avatarDir = '/storage/images/thread/';

        if ($this->avatar) {
            return $avatarDir . $this->avatar;
        }

        return $avatarDir . 'default.png';
    }

    public function title()
    {
        if ($this->isSingleChat()) {
            if ($participant = $this->participant()) {
                return $participant->name;
            }
        }

        return $this->subject;
    }

    public function addAvatar($file)
    {
        $storageDir = '/public/images/thread/';

        if (!Storage::exists($storageDir)) {
            Storage::makeDirectory($storageDir);
        }

        $file->store($storageDir);

        $this->avatar = $file->hashName();

        $this->save();
    }

    public function isSingleChat()
    {
        return $this->thread_type == 'single';
    }

    public function isGroupChat()
    {
        return $this->thread_type == 'group';
    }

    /**
     * Scope a query to only include unapproved organisations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSingleChat($query)
    {
        return $query->where('thread_type', 'single');
    }

    /**
     * Scope a query to only include unapproved organisations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroupChat($query)
    {
        return $query->where('thread_type', 'group');
    }
}
