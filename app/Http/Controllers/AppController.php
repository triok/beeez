<?php

namespace App\Http\Controllers;

use App\Filters\JobFilters;
use App\Models\Jobs\Job;
use App\Models\Page;
use App\Models\Jobs\Skill;

class AppController extends Controller
{
    public function index(JobFilters $filters)
    {
        /** @var Job $jobs */
        $jobs = Job::filter($filters)
            ->whereNotIn('status', [config('enums.jobs.statuses.DRAFT'), config('enums.jobs.statuses.PRIVATE')])
            ->orderBy('created_at', 'desc')
            ->paginate(request('count', 20));

        $jobsTotalByYear = Job::whereNotIn('status', [config('enums.jobs.statuses.DRAFT'), config('enums.jobs.statuses.PRIVATE')])
            ->whereYear('created_at', date('Y'))
            ->count();

        $skills = Skill::all();

        $selectedSkill = request('skill');

        return view('home',compact('jobs', 'jobsTotalByYear','skills','selectedSkill'));
    }

    function showPage($id)
    {
        $page = Page::find($id);
        return view('page', compact('page'));
    } 

}
