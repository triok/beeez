<?php

namespace App\Http\Controllers;

use App\Filters\JobFilters;
use App\Models\Jobs\Job;
use App\Queries\JobQuery;
use Illuminate\Http\Request;

class AppController extends Controller
{

    public function index(JobFilters $filters){

        if(isset($_GET['job'])){

            $search = $_GET['job'];
            $jobs = Job::orderBy('created_at','DESC')
                ->orWhere('id',$search)
                ->orWhere('name','like','%'.$search.'%')
                ->orWhere('desc','like','%'.$search.'%')
                ->where('status',config('enums.jobs.statuses.OPEN'))
                ->with(['tag'])
                ->paginate(20);

        }else{
            $jobs = JobQuery::onlyOpen()->paginate(20);
        }
        return view('home',compact('jobs'));

        //return Job::filter($filters)->get();
    }

}
