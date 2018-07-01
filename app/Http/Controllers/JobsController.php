<?php

namespace App\Http\Controllers;

use App\Mail\AdminNewJobAppNotice;
use App\Mail\ShareJob;
use App\Models\Billing\Payouts;
use App\Models\File;
use App\Models\Jobs\Applications;
use App\Models\Jobs\Bookmarks;
use App\Models\Jobs\Categories;
use App\Models\Jobs\DifficultyLevel;
use App\Models\Jobs\JobCategories;
use App\Models\Jobs\Jobs;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class JobsController extends Controller
{
    /** @var \Illuminate\Support\Collection $usernames */
    protected $usernames;

    function __construct()
    {
        $this->usernames = User::query()->pluck('username');

        $this->middleware('auth');
        $this->middleware('permission:read-jobs',['only'=>['jobsAdmin']]);
        $this->middleware('permission:read-jobs-manager',['only'=>['create','store','jobsAdmin']]);
        $this->middleware('permission:update-jobs',['only'=>['edit','update','updateJobStatus']]);
        $this->middleware('permission:delete-jobs',['only'=>['destroy']]);

    }

    /**
     * @param $id
     * @return Mixed
     */
    function show($id)
    {
        $job = Jobs::find($id);

        if (request()->ajax()) {
            $job->level = $job->difficulty->name;

            $bookmark = Bookmarks::where('job_id', $id)->where('user_id', Auth::user()->id)->first();
            // TODO altered this code
            if (isset($bookmark))
                $job->bookmark = $bookmark->id;
            else
                $job->bookmark = 0;
            //skills
            $j = new Jobs();
            $job->prettyskills = $j->formatSkills($job->skills);
            $job->cats = $j->formatCats($job->categories);
            $job->price = env('CURRENCY_SYMBOL').$job->price;
            $job->files = File::query()->where('fileable_id', $id)->get();
            $job->posted = "Posted " . Carbon::parse($job->created_at)->diffForHumans();

            echo json_encode($job->toArray());
            //return view('jobs.show', compact('job'));
        } else {
            return view('jobs.show', compact('job'));
        }
    }

    /**
     * @param $id
     * @throws \Exception
     */
    function destroy($id)
    {
        $job = Jobs::findOrFail($id);
        //delete bookmarks
        foreach ($job->bookmarks as $bookmark) {
            $bookmark->delete();
        }
        //delete applications
        foreach ($job->applications as $app) {
            $app->delete();
        }

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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function jobsByCategories($id)
    {
        $category = Categories::find($id);
        $jobs = $category->openJobs()->paginate(20);
        $title = 'Jobs under ' . ucwords($category->name) . ' category';
        return view('home', compact('jobs', 'category', 'title'));
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

            $job = Jobs::find($request->job_id);
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function jobsAdmin()
    {
        $jobs = Jobs::paginate(20);
        return view('jobs.admin', compact('jobs'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function create()
    {
        Session::forget('job.files');
        $difficultyLevels = DifficultyLevel::pluck('name', 'id');
        $categories = Categories::orderBy('cat_order', 'ASC')->get();

        return view('jobs.edit', ['difficultyLevels' => $difficultyLevels, 'categories' => $categories, 'usernames' => $this->usernames]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function store(Request $request)
    {
        $rules = [
            'name'          => 'required|max:50',
            'price'         => 'required',
            'categories'    => 'required',
            'time_for_work' => 'required',
            'access'        => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            /** Clear session if errors validation*/
            Session::forget('job.files');

            return redirect()->back()->withErrors($validator)->withInput();
        }

        /** @var Jobs $job */
        $job = Jobs::create($request->all());

        $query = User::query()->where('username', $request->user);

        if($query->exists()) {
            /** @var User $user */
            $user = $query->first();

            $job->user_id = $user->id;
            $job->save();
            $job->applications()->create([
                'user_id'   => $user->id,
                'status'    => 'pending',
                'job_price' => $request->price
            ]);
        }


        if (Session::has('job.files')) {

            foreach (Session::get('job.files') as $file) {
                $job->files()->create([
                    'file'          => $file['file'],
                    'size'          => $file['size'],
                    'type'          => $file['type'],
                    'original_name' => $file['original_name'],
                ]);
            }
            Session::forget('job.files');
        }

        if (is_array($request->categories)) {
            foreach ($request->categories as $cat) {
                JobCategories::create(['category_id' => $cat, 'job_id' => $job->id]);
            }
        }
        if (is_array($request->skills)) {
            foreach ($request->skills as $skill) {
                DB::table('job_skills')->insert(
                    [
                        'job_id' => $job->id,
                        'skill_id' => $skill
                    ]
                );
            }
        }
        flash()->success('Job has been posted!');
        return redirect('/jobs/' . $job->id . '/edit');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function edit($id)
    {
        $job = Jobs::findOrFail($id);
        $categories = Categories::get();
        $difficultyLevels = DifficultyLevel::pluck('name', 'id');

        return view('jobs.edit', ['job' => $job, 'difficultyLevels' => $difficultyLevels, 'categories' => $categories, 'usernames' => $this->usernames]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:50',
            'price' => 'required',
            'categories' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $job = Jobs::find($id);
        $job->fill($request->all());
        $job->save();

        if (is_array($request->categories)) {
            JobCategories::whereJobId($id)->delete();//delete old if any
            foreach ($request->categories as $cat) {
                JobCategories::create(['category_id' => $cat, 'job_id' => $job->id]);
            }
        }

        if (is_array($request->skills)) {
            DB::table('job_skills')->where('job_id', $id)->delete();

            foreach ($request->skills as $skill) {
                DB::table('job_skills')->insert(
                    [
                        'job_id' => $id,
                        'skill_id' => $skill
                    ]
                );
            }
        }
        flash()->success('Job updated!');
        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    function updateJobStatus(Request $request)
    {
        if ($request->ajax()) {
            $job = Jobs::find($request->job_id);
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
        $job = Jobs::findOrFail($job_id);
        $application = Applications::findOrFail($app_id);
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
}
