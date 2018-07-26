<?php

namespace App\Http\Controllers;

use App\Filters\JobFilters;
use App\Models\Jobs\Job;

class AppController extends Controller
{
    public function index(JobFilters $filters)
    {
        /** @var Job $jobs */
        $jobs = Job::filter($filters)
            ->whereNotIn('status', [config('enums.jobs.statuses.DRAFT'), config('enums.jobs.statuses.PRIVATE')])
            ->orderBy('created_at', 'desc')
            ->paginate(request('count', 20));

        return view('home',compact('jobs'));
    }
}
