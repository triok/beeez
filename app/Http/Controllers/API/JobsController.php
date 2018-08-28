<?php

namespace App\Http\Controllers\API;

use App\Filters\JobFilters;
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
}
