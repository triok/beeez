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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();

        return view('threads.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $user_id = $this->getUserId($request);

        $thread_type = $request->get('thread_type', 'single');

        $subject = $request->get('subject', auth()->user()->name);

        // Если чат не групповой и уже есть созданный чат с этими пользователями то открываем его
        if ($user_id && $thread_type != 'group') {
            if ($thread_id = $this->getExistingThreadId($user_id)) {
                dd($thread_id);
                return redirect(route('messages.show', $thread_id));
            }
        }

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'thread_type' => $thread_type,
            'subject' => $subject,
            'description' => $request->get('description'),
        ]);

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $thread->addAvatar($request->file('avatar'));
        }

        $this->addUserToThread(Auth::user(), $thread);

        if ($user = User::find($user_id)) {
            $this->addUserToThread($user, $thread);
        }

        if ($thread->thread_type == 'group') {
            $this->addUsersToThread($request, $thread);
        }

        return redirect(route('messages.show', $thread->id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        if (!$thread->isGroupChat()) {
            flash()->error('Access denied!');

            return redirect(route('messages'));
        }

        $users = User::where('id', '!=', auth()->id())->get();

        $connections = Participant::where('thread_id', $thread->id)
            ->where('user_id', '!=', auth()->id())
            ->get();

        return view('threads.edit', compact('users', 'connections', 'thread'));
    }


    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function update(Request $request, Thread $thread)
    {
        if (!$thread->isGroupChat()) {
            flash()->error('Access denied!');

            return redirect(route('messages'));
        }

        if ($request->has('subject')) {
            $thread->subject = $request->get('subject');
        }

        if ($request->has('description')) {
            $thread->description = $request->get('description');
        }

        $thread->save();

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $thread->addLogo($request->file('logo'));
        }

        if ($thread->thread_type == 'group') {
            $this->addUsersToThread($request, $thread);
        }

        flash()->success('Thread updated!');

        return redirect(route('messages.show', $thread->id));
    }

    /**
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function destroy(Thread $thread)
    {
        if (auth()->id() != $thread->user_id) {
            flash()->error('Access denied!');

            return redirect(route('messages'));
        }

        $thread->delete();

        flash()->success('Done.');

        return redirect(route('messages'));
    }

    protected function addUserToThread(User $user, Thread $thread)
    {
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'last_read' => new Carbon,
        ]);
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
            return $request->get('user_id');
        }

        return null;
    }

    protected function getExistingThreadId($user_id)
    {
        $threadIds = Participant::where('user_id', auth()->user()->id)
            ->pluck('thread_id')->toArray();

        $participant = Participant::whereIn('thread_id', array_values($threadIds))
            ->where('user_id', $user_id)
            ->latest('updated_at')
            ->first();

        if ($participant) {
            $thread = Thread::find($participant->thread_id);

            if ($thread && $thread->thread_type == 'single') {
                return $participant->thread_id;
            }
        }

        return null;
    }

    /**
     * Add connections.
     *
     * @param Request $request
     * @param Thread $thread
     * @throws \Exception
     */
    protected function addUsersToThread(Request $request, Thread $thread)
    {
        if ($request->has('connections')) {
            $connectionIds = Participant::where('thread_id', $thread->id)
                ->where('user_id', '!=', $thread->user_id)
                ->pluck('thread_id', 'user_id')
                ->toArray();

            foreach ($request->get('connections') as $user_id => $value) {
                if (isset($connectionIds[$user_id])) {
                    unset($connectionIds[$user_id]);
                } else if ($user = User::find($user_id)) {
                    $this->addUserToThread($user, $thread);
                }
            }

            foreach ($connectionIds as $user_id => $thread_id) {
                Participant::where('thread_id', $thread->id)
                    ->where('user_id', $user_id)
                    ->delete();
            }
        } else {
            Participant::where('thread_id', $thread->id)
                ->where('user_id', '!=', $thread->user_id)
                ->delete();
        }
    }
}
