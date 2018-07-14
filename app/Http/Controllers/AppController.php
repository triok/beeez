<?php

namespace App\Http\Controllers;

use App\Models\Jobs\Job;
use App\Queries\JobQuery;
use Illuminate\Http\Request;

class AppController extends Controller
{

    function index(){
        if(isset($_GET['job'])){

            $search = $_GET['job'];
            $jobs = Job::orderBy('created_at','DESC')
                ->orWhere('id',$search)
                ->orWhere('name','like','%'.$search.'%')
                ->orWhere('desc','like','%'.$search.'%')
                ->where('status',config('enums.jobs.statuses.OPEN'))->paginate(20)
                ->with(['tag']);
        }else{
            $jobs = JobQuery::onlyParentAndOpen()->with(['tag'])->paginate(20);
        }
        return view('home',compact('jobs'));
    }

}
