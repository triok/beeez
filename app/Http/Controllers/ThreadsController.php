<?php

namespace App\Http\Controllers;

use App\Models\Jobs\Job;
use App\Models\Participant;
use App\Models\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $this->getUserId($request);

        if (!$user_id) {
            flash()->error('The thread was not created.');

            return redirect()->route('messages');
        }

        if ($thread_id = $this->getExistingThreadId($user_id)) {
            return redirect(route('messages.show', $thread_id));
        }

        $thread = Thread::create([
            'subject' => auth()->user()->name,
        ]);

        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);

        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $user_id,
            'last_read' => new Carbon,
        ]);

        return redirect(route('messages.show', $thread->id));
    }

    protected function getUserId(Request $request)
    {
        if ($request->has('job_id')) {
            $job = Job::find($request->get('job_id'));

            if ($job) {
                return $job->user_id;
            }
        }

        if ($request->has('user_id') && $request->get('user_id') != auth()->user()->id) {
            $user = User::find($request->get('user_id'));

            if ($user) {
                return $user->id;
            }
        }

        return null;
    }

    protected function getExistingThreadId($user_id)
    {
        $threadIds = Participant::where('user_id', auth()->user()->id)->pluck('thread_id')->toArray();

        $participant = Participant::whereIn('thread_id', array_values($threadIds))
            ->where('user_id', $user_id)
            ->latest('updated_at')
            ->first();

        if ($participant) {
            return $participant->thread_id;
        }

        return null;
    }
}
