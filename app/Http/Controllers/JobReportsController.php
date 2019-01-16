<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Jobs\Job;
use App\Notifications\JobOwnerReportNotification;
use App\Notifications\JobReportNotification;
use App\User;
use Illuminate\Http\Request;

class JobReportsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReportRequest $request
     * @param Job $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReportRequest $request, Job $job)
    {
        $job->reports()->create([
            'user_id' => auth()->id(),
            'body' => $request->get('body')
        ]);

        if ($job->user_id != auth()->id()) {
            $recipient = User::find($job->user_id);

            $user = User::find(auth()->id());

            $recipient->notify(new JobOwnerReportNotification($job, $user, $request->get('body')));
        }

        if(auth()->id() == $job->user_id) {
            flash()->success('Запрос отправлен.');
        } else{
            flash()->success('Отчет добавлен.');
        }

        return redirect()->back();
    }

    /**
     * Notify.
     *
     * @param Request $request
     * @param Job $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notify(Request $request, Job $job)
    {
        $recipient = User::find($request->get('user_id'));

        if ($recipient) {
            $job->reports()->create([
                'user_id' => auth()->id(),
                'body' => 'Пользователем ' . auth()->user()->name . ' был запрошен отчет по заданию.'
            ]);

            $recipient->notify(new JobReportNotification($job));

            flash()->success('Запрос отправлен.');
        } else {
            flash()->error('Пользователь не найден!');
        }

        return redirect()->back();
    }
}