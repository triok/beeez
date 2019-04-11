<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalRequest;
use App\Mail\AdminNewJobAppNotice;
use App\Models\Jobs\Application;
use App\Models\Jobs\Job;
use App\Models\Jobs\Proposal;
use App\Models\Team;
use App\Notifications\JobConfirmNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;


class JobProposalsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProposalRequest $request
     * @param Job $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProposalRequest $request, Job $job)
    {

        $validator = Validator::make($request->all(), [
            'up_to'   => 'required'
        ]);

        if ($job->proposals()->where('user_id', auth()->id())->count()) {
            flash()->error('Вы уже добавили предложение!');

            return redirect()->back();
        }

        $job->proposals()->create([
            'proposal_type' => $this->getProposalType($request),
            'user_id' => auth()->id(),
            'body' => $request->get('body'),
            'up_to' => Carbon::createFromFormat('d.m.Y H:i', $request->get('up_to'))->format('Y-m-d H:i:s')
        ]);

        flash()->success('Ваше предложение добавлено.');

        return redirect()->back();
    }

    /**
     * @param Job $job
     * @param Proposal $proposal
     * @return null|void
     */
    function apply(Job $job, Proposal $proposal, Request $request)
    {

        if (Carbon::now() > $proposal->up_to || Carbon::now() > $job->end_date || $job->application()->exists() ) {
            flash()->error('Просрочена актуальность задания или предложения.');
            return redirect()->back();
        }

        $job->update(['price' => $request->input('amount')]);

        $applicant = Application::query()->create([
            'user_id' => $proposal->user->id,
            'job_id' => $job->id,
            'deadline' => $job->end_date,
            'job_price' => $job->price,
            'status' => config('enums.applications.statuses.IN_PROGRESS')
        ]);

        $job->update(['status' => config('enums.applications.statuses.IN_PROGRESS')]);

        if($proposal->proposal_type && $team = Team::find($proposal->proposal_type)) {
            $project = $team->projects()->create([
                'user_id' => $proposal->user_id,
                'name' => 'Без проекта',
                'project_type' => 'team',
                'description' => '',
                'is_temporary' => true
            ]);

            $job->update(['team_project_id' => $project->id]);
        }
   


        try {
            // Mail::to(env('MAIL_FROM_ADDRESS', env('MAIL_FROM_NAME')))->send(new AdminNewJobAppNotice($job, $applicant));
            flash()->success('Предложение принято.');
            $recipient = User::find($proposal->user->id);
            $recipient->notify(new JobConfirmNotification($job));            
        } catch (Exception $e) {
            flash()->error('Application has been saved but we had trouble notifying job poster. Please contact them directly.');
        }

        return redirect()->back();
    }

    /**
     * Proposal type.
     *
     * @param Request $request
     * @return int
     */
    protected function getProposalType(Request $request)
    {
        $proposalType = 0;

        if ((int)$teamId = $request->get('proposal_type')) {
            $teams = Auth::user()->proposalTeams();

            $teams->search(function ($item) use ($teamId) {
                return $item->id == $teamId;
            });

            if ($teams->count()) {
                return $teamId;
            }
        }

        return $proposalType;
    }
}