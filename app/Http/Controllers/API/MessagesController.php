<?php

namespace App\Http\Controllers\API;

use App\Models\Message;
use App\Models\Participant;
use App\Models\Thread;
use App\Transformers\MessageTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    protected $transformer;

    function __construct(MessageTransformer $transformer)
    {
        $this->transformer = $transformer;

        $this->middleware('auth');
    }

    public function index(Thread $thread)
    {
        $this->updateLastRead($thread);

        $messages = Message::where('thread_id', $thread->id)->get();

        $data = $this->transformer->transformCollection($messages);

        $data['thread'] = $thread;

        $data['auth_user_id'] = Auth::id();

        return response()->json($data);
    }

    protected function updateLastRead(Thread $thread)
    {
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);

        $participant->last_read = new Carbon;

        $participant->save();
    }
}
