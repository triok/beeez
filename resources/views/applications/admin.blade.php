@extends('layouts.app')
@section('content')
    <h2>Applications manager</h2>
    <div id="jobs">

        <div class="input-group col-xs-6">
            <input class="search form-control input-sm" placeholder="Search"/>
            <span class="input-group-btn">
            <button class="btn btn-default btn-sm" data-sort="job">
                <i class="fa fa-search"></i>
            </button>
            </span>
        </div>
        <div class="list">

            @foreach($jobs as $job)
                <div class="row small" style="border-bottom: solid 1px #ccc;padding:5px 0">
                    <div class="col-xs-2">{{$job->created_at}}</div>
                    <div class="col-xs-5 job">

                        <a data-toggle="tooltip" title="Open conversation"
                           href="/jobs/{{$job->id}}">{{$job->name}}</a>

                        @if($job->status =="approved")
                            <a class="btn btn-default btn-xs pull-right"
                               data-toggle="tooltip"
                               title="Open conversation"
                               href="/job/{{$job->id}}/{{$job->id}}/work">
                                <i class="fa fa-envelope-open-o"></i> </a>
                        @endif
                    </div>
                    <div class="col-xs-1">
                        <a href="/jobs/{{$job->id}}" data-toggle="tooltip" title="Manage jobs">
                            <i class="fa fa-pencil-square-o"></i></a>
                    </div>
                    <div class="col-xs-3 applicant">
                        <a data-toggle="tooltip" title="View user profile"
                           href="/users/{{$job->user->id}}/view">{{$job->user->name}}</a></div>
                    <div class="col-xs-1 status">
                        {!! $job->prettyStatus !!}
                    </div>
                </div>
            @endforeach
        </div>

        {{$jobs->links()}}
    </div>
@endsection