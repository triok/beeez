<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Message;
use App\Models\Participant;
use App\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class MessagesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('messenger.index');
    }

    public function show()
    {
        return view('messenger.index');
    }

    public function update($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            flash()->error('The thread was not found.');

            return redirect()->route('messages');
        }

        // $thread->activateAllParticipants();

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => Input::get('message'),
        ]);

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);

        if ($files = Input::get('files')) {
            foreach ($files as $file) {
                $message->files()->create([
                    'title' => $file['title'],
                    'path' => $file['path']
                ]);
            }
        }

        $participant->last_read = new Carbon;

        $participant->save();

        return redirect()->route('messages.show', $id);
    }
}