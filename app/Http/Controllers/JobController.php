<?php

namespace App\Http\Controllers;

use App\Filters\JobFilters;
use App\Http\Requests\StoreJobRequest;
use App\Jobs\AddApplicationsJob;
use App\Jobs\AddCategoriesJob;
use App\Jobs\AddFilesJob;
use App\Jobs\AddSkillsJob;
use App\Jobs\AddSubTasksJob;
use App\Jobs\AddTagJob;
use App\Mail\ComplainToAdmin;
use App\Mail\ShareJob;
use App\Models\Billing\Payouts;
use App\Models\File;
use App\Models\Jobs\Application;
use App\Models\Jobs\Bookmark;
use App\Models\Jobs\Category;
use App\Models\Jobs\DifficultyLevel;
use App\Models\Jobs\JobCategories;
use App\Models\Jobs\Job;
use App\Models\Jobs\Skill;
use App\Models\Jobs\TimeForWork;
use App\Models\Project;
use App\Models\Team;
use App\Queries\JobQuery;
use App\User;
use App\Queries\UserQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class JobController extends Controller
{
    /** @var \Illuminate\Support\Collection $usernames */
    protected $usernames;

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-jobs',['only'=>['jobsAdmin']]);
        $this->middleware('permission:read-jobs-manager',['only'=>['jobsAdmin']]);
        $this->middleware('permission:update-jobs',['only'=>['edit','update','updateJobStatus']]);
        $this->middleware('permission:delete-jobs',['only'=>['destroy']]);

        $this->usernames = User::query()->pluck('username', 'id');
    }

    public function index(JobFilters $filters)
    {
        $jobs = Job::filter($filters)->whereNotIn('status', [config('enums.jobs.statuses.DRAFT')])
            ->paginate(request('count', 20));

        $title =  count($jobs) > 0 ? 'All jobs with tag: '. request()->tag : null;

        return view('home', ['jobs' => count($jobs) > 0 ? $jobs : Job::query()->paginate(request('count', 20)),
                'title' => $title]);
    }

    /**
     * @param Job $job
     * @return Mixed
     */
    function show(Job $job)
    {

        $job->addView();

//        if (request()->ajax()) {
//            $job->level = $job->difficulty->name;
//
//            $bookmark = Bookmark::where('job_id', $job->id)->where('user_id', Auth::user()->id)->first();
//
//            if (isset($bookmark))
//                $job->bookmark = $bookmark->id;
//            else
//                $job->bookmark = 0;
//            //skills
//            $j = new Job();
//            $job->prettyskills = $j->formatSkills($job->skills);
//            $job->cats = $j->formatCats($job->categories);
//            $job->price = env('CURRENCY_SYMBOL').$job->price;
//            $job->files = File::query()->where('fileable_id', $job->id)->get();
//            $job->posted = "Posted " . Carbon::parse($job->created_at)->diffForHumans();
//            $job->viewed = 'Viewed (' . $job->getViews() .')';
//            $job->jobs   = $job->load('jobs');
//            $job->parent = $job->load('parent');
//            $job->parentJobs = $job->parent()->with('jobs')->get();
//
            $job->with(['tag', 'user', 'jobs', 'parent', 'categories']);
//
//            return json_encode($job->toArray());
////            return view('jobs.show', compact('job'));
//        }
        $time = TimeForWork::find($job->time_for_work);
        $application = $job->applications()->first();

        if($application) {
            $application = $application->user;
        }

        $users = UserQuery::users();


        $jobTeam = $this->getJobTeam($job);

        return view('jobs.show', compact('job', 'users', 'application', 'jobTeam', 'time'));
    }

    protected function getJobTeam(Job $job)
    {
        if($job->user_id == Auth::id()) {
            return false;
        }

        $project = $job->teamProject;

        if(!$project || !$project->team_id) {
            return false;
        }

        $team = Auth::user()->allUserTeams()
            ->where('id', $project->team_id)
            ->first();

        return $team;

    }

    /**
     * @param Job $job
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    function destroy(Job $job)
    {
        if(request()->ajax()) {
            $job->bookmark()->delete();
            $job->applications()->delete();

            if ($job->delete()) {
                $status = 'success';
                $msg = 'Job has been unlisted';
                flash()->success('Job has been unlisted');
            } else {
                $status = 'error';
                $msg = 'Error deleting job';
                flash()->success('Error deleting job');
            }
            echo json_encode(['status' => $status, 'message' => $msg]);
        }

        $job->bookmark()->delete();
        $job->applications()->delete();
        $job->delete();

        flash()->success('Job has been unlisted');

        return redirect()->back();
    }

    /**
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function jobsByCategories(Category $category)
    {
        $jobs = $category->jobs()->whereNotIn('status', [config('enums.jobs.statuses.DRAFT'), config('enums.jobs.statuses.PRIVATE')])->paginate(20);

        $title = 'Job under ' . ucwords($category->name) . ' category';

        $jobsTotalByYear = Job::whereNotIn('status', [config('enums.jobs.statuses.DRAFT'), config('enums.jobs.statuses.PRIVATE')])
            ->whereYear('created_at', date('Y'))
            ->count();

        $skills = Skill::all();

        $selectedSkill = request('skill');

        return view('home', compact('jobs', 'category', 'title', 'jobsTotalByYear', 'skills', 'selectedSkill'));
    }

    /**
     * @param Request $request
     */
    function shareJob(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'email' => 'required|email',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $msg = 'Please check your fields again!';
                $status = 'error';
                echo json_encode(['status' => $status, 'message' => $msg]);
                return;
            }

            $job = Job::find($request->job_id);
            $job->message = $request->message;
            try {
                Mail::to($request->email)->send(new ShareJob($job));
                $status = 'success';
                $msg = 'Message has been sent. Thank you for sharing';
            } catch (Exception $e) {
                $status = 'error';
                $msg = 'We had trouble sending your request. Please try again.';
            }

            echo json_encode(['status' => $status, 'message' => $msg]);
        }
    }

    function complainJob(Request $request)
    {
        if ($request->ajax()) {

            $job = Job::find($request->job_id);
            $job->message = $request->message;
            try {
                Mail::to(env('MAIL_ADMIN'))->send(new ComplainToAdmin($job));
                $status = 'success';
                $msg = 'Message has been sent.';
            } catch (Exception $e) {
                $status = 'error';
                $msg = 'We had trouble sending your request. Please try again.';
            }

            echo json_encode(['status' => $status, 'message' => $msg]);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function jobsAdmin()
    {
        $jobs = Job::paginate(20);

        return view('jobs.admin', compact('jobs'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function create()
    {
        Session::forget('job.files');

        unset($this->usernames[Auth::id()]);

        $projects = auth()->user()->allUserProjects()->get();
        $skills = Skill::all();
        $times = TimeForWork::pluck('value');

        return view('jobs.edit', ['usernames' => $this->usernames, 'projects' => $projects, 'skills' => $skills,'times' => $times]);
    }


    function store(StoreJobRequest $request)
    {
        
        if($request->get('end_date')) {
            $request->merge([
                'end_date' => Carbon::createFromFormat('d.m.Y H:i', $request->get('end_date'))->format('Y-m-d H:i:s')
            ]);
        }

        if(!$request->get('desc')) $request->merge(['desc' => '']);
        if(!$request->get('price')) $request->merge(['price' => 0]);

        $job = Job::query()->create(array_intersect_key($request->all(), array_flip(Job::getAllAttributes())));

        dispatch(new AddFilesJob($job, 0));

        if($request->get('category_id')) {
            dispatch(new AddCategoriesJob($job));
        }

        dispatch(new AddSkillsJob($job));
        dispatch(new AddApplicationsJob($job));
        dispatch(new AddSubTasksJob($job));
        dispatch(new AddTagJob($job));

        if ($request->has('draft')) {
            $job->update(['status' => config('enums.jobs.statuses.DRAFT')]);
        }

        if ($request->has('user')) {
            $job->update(['status' => config('enums.jobs.statuses.PRIVATE')]);
        }

        $this->addJobToProject($job);

        flash()->success('Job has been posted!');

        if ($request->get('project_id') && $project = Project::find($request->get('project_id'))) {
            return redirect(route('projects.show', $project));
        } else {
            return redirect(route('my-applications') . '#client');
        }
    }

    function edit(Job $job)
    {
        unset($this->usernames[Auth::id()]);

        $projects = auth()->user()->allUserProjects()->get();

        return view('jobs.edit', ['job' => $job, 'usernames' => $this->usernames, 'projects' => $projects]);
    }

    function editProject(Job $job)
    {
        if(!$team = $this->getJobTeam($job)) {
            flash()->success('Access denied!');

            return redirect()->back();
        }

        return view('jobs.edit-project', [
            'job' => $job,
            'projects' => $team->projects
        ]);
    }

    function updateProject(Request $request, Job $job)
    {
        if(!$team = $this->getJobTeam($job)) {
            flash()->success('Access denied!');

            return redirect()->back();
        }

        $oldProject = $job->teamProject;

        if($request->get('team_project_id')) {
            $job->team_project_id = $request->get('team_project_id');

            $job->save();
        }

        if($oldProject->is_temporary) {
            $oldProject->delete();
        }
        
        flash()->success('Job updated!');

        return redirect(route('jobs.show', $job));
    }


    function update(StoreJobRequest $request, Job $job)
    {
        if($request->get('end_date')) {
            $request->merge([
                'end_date' => Carbon::createFromFormat('d.m.Y H:i', $request->get('end_date'))->format('Y-m-d H:i:s')
            ]);
        }

        if(!$request->get('desc')) $request->merge(['desc' => '']);
        if(!$request->get('price')) $request->merge(['price' => 0]);

        $job->update(array_intersect_key($request->all(), array_flip(Job::getAllAttributes())));

        dispatch(new AddFilesJob($job));

        if($request->get('category_id')) {
            dispatch(new AddCategoriesJob($job));
        }
        
        dispatch(new AddSkillsJob($job));
        dispatch(new AddApplicationsJob($job));
        dispatch(new AddTagJob($job));
        dispatch(new AddSubTasksJob($job));

        if ($request->has('draft')) {
            $job->update(['status' => config('enums.jobs.statuses.DRAFT')]);
        }

        $this->addJobToProject($job);

        flash()->success('Job updated!');

        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    function updateJobStatus(Request $request)
    {
        if ($request->ajax()) {
            $job = Job::find($request->job_id);
            $job->status = $request->status;
            $job->save();
            echo json_encode(['status' => 'success', 'message' => 'Job status set to ' . $request->status]);
        }
    }

    /**
     * @param $job_id
     * @param $app_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function work($job_id, $app_id)
    {
        $job = Job::findOrFail($job_id);
        $application = Application::findOrFail($app_id);
        $payout = Payouts::where('application_id',$app_id)->first();

        $authorize_request_body = array(
            'response_type' => 'code',
            'scope' => 'read_write',
            'client_id' => Config::get('app.stripe_client_id')
        );
        $stripeUrl = Config::get('app.stripe_authorize_uri') . '?' . http_build_query($authorize_request_body);

        if ($application->user_id == Auth::user()->id
            || ($application->user_id !== Auth::user()->id && \Trust::can('update-payouts')))
            return view('jobs.work', compact('job', 'application','payout','stripeUrl'));

        return view('errors.404');

    }

    public function subtask()
    {
        return view('jobs.partials.form', [
            'subtask' => true,
            'task_id' => request()->get('task_id', 2),
            'project_id' => (int)request()->get('project_id', 0),
            'usernames' => $this->usernames,
            'skills' => Skill::all(),
            'times' => TimeForWork::pluck('value'),
            'projects' => auth()->user()->allUserProjects()->get()
        ]);
    }

    protected function addJobToProject(Job $job) {
        if (request()->has('project_id')) {
            $project = Project::find(request()->get('project_id'));

            if($project) {
                $job->update(['project_id' => request()->get('project_id')]);
            } else {
                $job->update(['project_id' => null]);
            }
        }
    }
}
