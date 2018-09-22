<?php

namespace App;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;

class MessageFiles extends Model
{
    protected $fillable = ['title', 'path'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function link()
    {
        return '/storage/files/' . $this->message->user_id . '/' . $this->path;
    }
}
