<?php

namespace App\Http\Controllers\API;

use App\Filters\JobFilters;
use App\Models\Jobs\Application;
use App\Models\Jobs\Bookmark;
use App\Models\Jobs\Category;
use App\Models\Jobs\Job;
use App\Transformers\JobTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{
    function __construct(JobTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index(Request $request)
    {
        $jobs = DB::table('jobs');

        if ($request->get('skill')) {
            $jobs->join('job_skills', 'jobs.id', '=', 'job_skills.job_id')
                ->where('job_skills.skill_id', $request->get('skill'));
        }

        if ($request->get('category_id')) {
            $jobs->join('job_categories', 'jobs.id', '=', 'job_categories.job_id')
                ->where('job_categories.category_id', $request->get('category_id'));
        }

        $jobs = $jobs->whereNotIn('jobs.status', [config('enums.jobs.statuses.DRAFT'), config('enums.jobs.statuses.PRIVATE')])
            ->orderBy('jobs.created_at', 'desc')
            ->select('jobs.*')
            ->get();

        return response()->json($this->transformer->transformCollection($jobs));
    }

    public function search(Request $request)
    {
        $jobs = Job::where('status', '!=', 'complete');

        if ($request->has('user_id')) {
            if ($request->has('bookmarks')) {
                $ids = Bookmark::where('user_id', $request->get('user_id'))->pluck('job_id');

                $jobs->whereIn('id', $ids->toArray());
            } else if ($request->has('application')) {
                $ids = Application::where('user_id', $request->get('user_id'))->pluck('job_id');

                $jobs->whereIn('id', $ids->toArray());
            } else {
                $jobs->where('user_id', $request->get('user_id'));
            }
        }

        $response = $this->transformer->transformCollection($jobs->get());

        if (!$response) {
            $response = ['data' => []];
        }

        return response()->json($response);
    }
}
