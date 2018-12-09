<?php

namespace App\Http\Controllers;

use App\Mail\AdminNewJobAppNotice;
use App\Mail\ApplicationConversationNotice;
use App\Mail\NewCommentMail;
use App\Mail\NotifyUserAppStatus;
use App\Mail\TaskReview;
use App\Mail\UserTaskReview;
use App\Models\Comment;
use App\Models\Jobs\Application;
use App\Models\Jobs\conversations;
use App\Models\Jobs\Job;
use App\Queries\JobQuery;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class ApplicationsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-job-applications', ['only' => ['show', 'applicationsAdmin']]);
        $this->middleware('permission:delete-application-message', ['only' => ['deleteMessage']]);
        $this->middleware('permission:create-application-message', ['only' => ['postMessage']]);
        $this->middleware('permission:update-job-applications', ['only' => ['changeStatus']]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function show($id)
    {
        $app = Application::findOrFail($id);
        return view('jobs.applications.show', compact('app'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function applicationsAdmin()
    {
        //$applications = Application::orderBy('created_at', 'DESC')->with('user')->get();
        $jobs = Auth::user()->jobs()->orderBy('created_at', 'DESC')->paginate(request('count', 15));

        return view('applications.admin', compact('jobs'));
    }

    public function review(Job $job)
    {
        if ($job->status == config('enums.jobs.statuses.IN_REVIEW')) return redirect()->back();

        $job->application()->update(['status' => config('enums.jobs.statuses.IN_REVIEW')]);
        $job->update(['status' => config('enums.jobs.statuses.IN_REVIEW')]);

        try {
            Mail::to($job->user->email, $job->user->name)->send(new TaskReview($job, auth()->user()));
            flash()->success('Your task was successfully submitted for review');

        } catch (Exception $e) {
            flash()->error('The task is sent for review, but we have problems with notifying about work plans. Please contact them directly.');
        }

        return redirect()->back();
    }

    public function rating(Job $job)
    {
        if($application = $job->applications()->first()) {
            if (auth()->user()->id == $job->user_id) {
                if ($job->status != config('enums.jobs.statuses.IN_REVIEW')) {
                    flash()->error('Your do not have an access to add comments for this job.');

                    return redirect()->back();
                }

                $this->addRatingForUser($application->user_id);

                $application->update(['status' => config('enums.jobs.statuses.COMPLETE')]);
                $job->update(['status' => config('enums.jobs.statuses.COMPLETE')]);

                return redirect()->back();
            }

            if (auth()->user()->id == $application->user_id) {
                if ($job->status != config('enums.jobs.statuses.COMPLETE')) {
                    flash()->error('Your do not have an access to add comments for this job.');

                    return redirect()->back();
                }

                $this->addRatingForUser($job->user_id);

                $application->update(['status' => config('enums.jobs.statuses.CLOSED')]);
                $job->update(['status' => config('enums.jobs.statuses.CLOSED')]);

                return redirect()->back();
            }
        }

        flash()->error('Your do not have an access to add comments for this job.');

        return redirect()->back();
    }

    protected function addRatingForUser($commentable_id)
    {
        $user = User::find($commentable_id);

        $rating = (in_array(request()->rating, ['negative', 'positive']) ? request()->rating : null);

        if ($rating == 'negative') {
            $user->rating_negative++;
        }

        if ($rating == 'positive') {
            $user->rating_positive++;
        }

        $user->save();

        Comment::query()->create([
            'commentable_id' => $commentable_id,
            'commentable_type' => User::class,
            'body' => request()->message,
            'rating' => $rating,
            'author_id' => auth()->id(),
            'author_type' => User::class,
        ]);

        flash()->success('Your comment was successfully added.');
    }

    /**
     * @param Job $job
     * @return null|void
     */
    function applyJob(Job $job)
    {
        if (Carbon::now() > $job->end_date || $job->application()->exists()) return;

        $applicant = Application::query()->create([
            'user_id' => auth()->id(),
            'job_id' => $job->id,
            'deadline' => $job->end_date,
            'job_price' => $job->price,
            'status' => config('enums.applications.statuses.IN_PROGRESS')
        ]);

        $job->update(['status' => config('enums.applications.statuses.IN_PROGRESS')]);

        try {
            Mail::to(env('MAIL_FROM_ADDRESS', env('MAIL_FROM_NAME')))->send(new AdminNewJobAppNotice($job, $applicant));
            flash()->success('Application has been sent successfully!');
        } catch (Exception $e) {
            flash()->error('Application has been saved but we had trouble notifying job poster. Please contact them directly.');
        }

        return redirect()->back();

//        if ($request->ajax()) {
//            $rules = [
//                'job_id' => 'required',
//            ];
//            $validator = Validator::make($request->all(), $rules);
//            if ($validator->fails()) {
//                $msg = 'Please try again!';
//                $status = 'error';
//                echo json_encode(['status' => $status, 'message' => $msg]);
//                return;
//            }
//            $job = Job::find($request->job_id);
//
//            if (Carbon::now() > $job->end_date) {
//                echo json_encode(['status' => 'error', 'message' => 'job is closed']);
//                return;
//            }
//
//            $applicant = new Application();
//            $applicant->job_id = $request->job_id;
//            $applicant->user_id = Auth::user()->id;
//            $applicant->remarks = $request->remarks;
//            $applicant->status = 'pending';
//            $applicant->job_price = $job->price;
//            $applicant->save();
//
//
//            try {
//                Mail::to(env('MAIL_FROM_ADDRESS', env('MAIL_FROM_NAME')))->send(new AdminNewJobAppNotice($job, $applicant));
//            } catch (Exception $e) {
//                echo json_encode(['status' => 'success', 'message' => 'Application has been saved but we had trouble notifying job poster. Please contact them directly.']);
//                return null;
//            }
//            echo json_encode(['status' => 'success', 'message' => 'Application has been sent successfully']);
//        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function appStatus($id)
    {
        if (request()->ajax()) {
            $app = Application::find($id);
            return view('jobs.application-status', compact('app'));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function myApplications()
    {
        $applications = Auth::user()
            ->applications()
            ->where('status', '!=', 'complete')
            ->with('job')
            ->paginate(request('count', 15));

        $clientapps = auth()->user()->jobs;

        $firstdeadline = Auth::user()
            ->applications()
            ->with('job')
            ->where('status', '=', 'in progress')
            ->orderBy('deadline', 'ASC')
            ->first();

        $appscomplete = Auth::user()
            ->applications()
            ->where([['user_id', '=', auth()->user()->id],['status', '=', 'complete']])->get();

        return view('applications.my-applications', compact('applications','clientapps','firstdeadline','appscomplete'));
    }

    /**
     * @param Request $request
     */
    function changeStatus(Request $request)
    {
        if ($request->ajax()) {
            $app = Application::find($request->app_id);
            $app->status = $request->status;
            $app->save();

            $app->notifyOnChangeStatus($app);

            echo json_encode(['status' => 'success', 'message' => 'Status updated']);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function postMessage(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'message' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $user = 'User';

            Conversations::create([
                'message' => $request->message,
                'user_id' => Auth::user()->id,
                'application_id' => $request->app_id
            ]);

            //notify users of a message
            $app = Application::find($request->app_id);
            $job = Job::find($app->job_id);
            //user is posting
            if ((int)Auth::user()->id == (int)$app->user_id) { //notify admin
                try {
                    Mail::to(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->send(new ApplicationConversationNotice($job, $app, 'admin'));
                } catch (Exception $e) {
                    echo json_encode(['status' => 'success', 'message' => 'Message has been saved but the notification serve did not respond.']);
                    return null;
                }
            } else { //notify user
                $owner = User::find($app->user_id);
                try {
                    Mail::to($owner->email, $owner->name)->send(new ApplicationConversationNotice($job, $app, 'user'));
                } catch (Exception $e) {
                    echo json_encode(['status' => 'success', 'message' => 'Message has been saved but the notification serve did not respond.']);
                    return null;
                }
            }
            echo json_encode(['status' => 'success', 'user' => $user, 'date' => date('d M, y')]);
        }
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    function deleteMessage(Request $request)
    {
        if ($request->ajax()) {
            $m = Conversations::find($request->message_id);
            $m->delete();
            echo 'success';
        }
    }
}
