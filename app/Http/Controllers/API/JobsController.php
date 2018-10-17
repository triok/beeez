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

class JobsController extends Controller
{
    function __construct(JobTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index(Request $request)
    {
        if ($request->has('category_id')) {
            $category = Category::find($request->get('category_id'));

            $jobs = $category->jobs();
        } else {
            $jobs = Job::select();
        }

        $jobs = $jobs->whereNotIn('status', [config('enums.jobs.statuses.DRAFT'), config('enums.jobs.statuses.PRIVATE')])
            ->orderBy('created_at', 'desc')
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
