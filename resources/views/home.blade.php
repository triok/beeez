@extends('layouts.app')

@section('content')
    <h3>
        @if(isset($title))
            {{$title}}
        @else
            @lang('home.title')
        @endif
    </h3>

    @if(isset($category))
        <div class="alert bg-info small">{{ ucwords($category->name) }}</div>
    @endif

    <div class="row current-jobs">
        <table class="table table-striped">
            <thead>
                <tr>
                  <th scope="col">@lang('home.task')</th>
                  <th scope="col">@lang('home.timefor')</th>
                  <th scope="col">@lang('home.before')</th>
                  <th scope="col">@lang('home.price')</th>
                  <th scope="col">@lang('home.work')</th>
                </tr>
            </thead>
            <tbody>

                @foreach($jobs as $job)

                <tr class="{!! count($job->applications) > 0 && auth()->check() ? 'in-progress': '' !!}">

                  <th scope="row" class="job-name">
                      <a href="{{route('jobs.show', $job)}}" id="{{$job->id}}">{{$job->name}}</a>
                      {!! $job->status == config('enums.jobs.statuses.IN_REVIEW') && isset($job->application) ? '<p class="label label-danger">Your task is under review</p>' : '' !!}

                  </th>
                  <td>{{ $job->time_for_work }} @lang('home.hours')</td>

                  <td>{{ \Carbon\Carbon::parse($job->end_date)->format('d M, Y') }} <b>{{ \Carbon\Carbon::parse($job->end_date)->format('H:i') }}</b></td>
                  <td>{{ $job->formattedPrice }}</td>
                  <td>
                      {{--{{dd($job->applications)}}--}}
                      @if(Auth::check())
                          @if(count($job->applications) > 0)
                              @if(isset($job->application))
                                  <button data-id="{{$job->id}}" {!! $job->status == config('enums.jobs.statuses.IN_REVIEW') ? 'disabled' : '' !!} class="btn btn-success btn-sm btn-review">
                                      <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                      @lang('home.complete')
                                  </button>
                              @else
                                  <button disabled class="btn btn-warning btn-sm "><i class="fa fa-history" aria-hidden="true"></i> @lang('home.in_progress') </button>
                              @endif
                          @else
                              @if(\Carbon\Carbon::now() <= $job->end_date)
                                  <form action="{{route('jobs.apply', $job)}}" method="post">
                                      {{csrf_field()}}
                                      <button class="btn btn-default btn-sm" type="submit"><i class="fa fa-briefcase"></i> @lang('home.apply') </button>
                                  </form>
                              @else
                                  <button disabled id="{{$job->id}}" class="btn btn-default btn-sm apply-job-btn"><i class="fa fa-briefcase"></i> @lang('home.enddate') </button>
                              @endif
                          @endif
                      @else
                          <button id="{{$job->id}}" class="btn btn-default btn-sm apply-job-btn"><i class="fa fa-briefcase"></i> @lang('home.apply') </button>
                      @endif
                  </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @foreach($jobs as $job)
            <div class='col-sm-6 col-xs-12 col-md-6 col-lg-6'>
                <div class="panel panel-default box">
                    <div class="panel-heading">
                        <h4 id="{{$job->id}}"><a href="{{route('jobs.show', $job)}}">{{$job->name}}</a></h4>
                        <span id="posted">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            Posted {{\Carbon\Carbon::parse($job->created_at)->diffForHumans()}} <small> by {{$job->user->username}}</small>
                        </span>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-5">
                                @if($job->difficulty)
                                <span class="label label-{{$job->difficulty->name=="advanced"?'danger':'info'}} level">{{$job->difficulty->name}}</span>
                                @endif
                            </div>
                            <div class="col-xs-2 text-center">
                                <span>{{$job->formattedPrice}}</span>
                            </div>
                            <div class="col-xs-5 text-right">
                                <span class="small">Ends: {{ \Carbon\Carbon::parse($job->end_date)->format('d M, Y H:i') }}</span>
                            </div>
                        </div>
                        <br/>
                        <div class="job-desc" id="{{$job->id}}">
                            {{str_limit(strip_tags($job->desc,200)) }}
                        </div>

                        @if($job->tag)
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('jobs.index')}}?tag={{$job->tag->value}}"> <span class="label label-warning">{{$job->tag->value}}</span></a>
                            </div>
                        </div>
                        @endif
                        
                        @foreach($job->skills as $skill)
                            <span class="label label-default">{{$skill->name}}</span>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            @if($job->status =="closed" && !Auth::user()->hasRole('admin'))
                                <div class="col-xs-3 col-xs-offset-4">
                                    <div class="label label-danger">Job is closed</div>
                                </div>
                            @else
                                <div class="col-xs-4">
                                    @if(Auth::check() && count($job->applications) > 0 && isset($job->application))
                                        <button id="{{$job->application->id}}" class="btn btn-success btn-sm job-app-status-btn"><i  class="fa fa-briefcase"></i> check status </button>
                                    @else
                                        @if(\Carbon\Carbon::now() <= $job->end_date)
                                            <button id="{{$job->id}}" class="btn btn-default btn-sm apply-job-btn"><i class="fa fa-briefcase"></i> apply </button>
                                        @else
                                            <button disabled id="{{$job->id}}" class="btn btn-default btn-sm apply-job-btn"><i class="fa fa-briefcase"></i> apply </button>
                                        @endif
                                    @endif

                                </div>
                                <div class="col-xs-5 text-center">

                                    @if(Auth::check() && isset($job->bookmark))
                                        <button id="{{$job->bookmark->id}}"
                                                class="btn btn-default btn-sm bookmark-job-remove"><i
                                                    class="fa fa-heart text-danger"></i>
                                        </button>
                                    @else
                                        <button id="{{$job->id}}" class="btn btn-default btn-sm bookmark-job"><i
                                                    class="fa fa-heart"></i>
                                        </button>
                                    @endif
                                    @role('admin')
                                    <a href="/jobs/{{$job->id}}" class="btn btn-default btn-xs"><i
                                                class="fa fa-pencil"></i>
                                    </a>
                                    <a href="#" class="btn btn-warning btn-xs" data-toggle="tooltip"
                                       title="Open/Close Job"
                                       onclick="updateJobStatus('{{$job->id}}','{{$job->status=='open'?'closed':'open'}}')">
                                        <i class="fa fa-{{$job->status=='open'?'close':'eye'}}"></i>
                                    </a>
                                    <a href="#" id="{{$job->id}}" class="btn btn-danger btn-xs delete delete-job"><i
                                                class="fa fa-trash"></i></a>

                                    @endrole

                                </div>
                                <div class="col-xs-3 text-right">
                                    <button id="{{$job->id}}" data-title="{{$job->name}}"
                                            class="btn btn-default btn-sm share-job-btn"><i
                                                class="fa fa-share"></i> share
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-sm-12">{{$jobs->links()}}</div>
    </div>
@endsection
@push('modals')
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="viewJobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="title"></h4>
                <span>
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <span class="posted-time"></span><small id="author"></small>
                    <span class="small pull-right" id="viewed"></span>
                </span>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-4">Difficulty:<span id="difficulty"></span></div>
                    <div class="col-xs-4">Price: <span id="price"></span></div>
                    <div class="col-xs-4">Ends: <span id="end-date"></span></div>
                </div>
                <hr/>

                <span id="desc"></span>
                <h5><strong>Skills</strong></h5>
                <span id="skills"></span>
                <h5><strong>Category</strong></h5>
                <span id="cats"></span>
                <span id="tag-modal"></span>

                <ul class="files-job list-group list-group-flush" id="files-job" style="padding-top: 15px;"></ul>
                <ul class="jobs-list list-group list-group-flush"></ul>
            </div>

            <div class="modal-footer">

                <button id="" class="btn btn-default btn-sm bookmark-modal-btn"><i class="fa fa-heart"></i></button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="applyJobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open(['url'=>'','class'=>'apply-job-form']) !!}
            <div class="modal-body">
                {!! Form::hidden('job_id') !!}
                {!! Form::textarea('remarks',null,['class'=>'form-control','rows'=>3,'placeholder'=>'Enter an optional notes']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button class="btn btn-primary btn-sm apply-job-form-btn">Send</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="shareJobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Share <span></span> Job</h4>
            </div>

            {!! Form::open(['url'=>'','class'=>'share-job-form']) !!}
            <div class="modal-body">
                {!! Form::hidden('job_id') !!}
                {!! Form::input('email','email',null,['required'=>'required','class'=>'form-control','placeholder'=>'Enter your friend\'s email']) !!}
                <br/>
                {!! Form::textarea('message',null,['class'=>'form-control','rows'=>3,'placeholder'=>'Enter an optional message']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button class="btn btn-primary btn-sm">Send</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="completeJobForm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">You can add some comment or files.</h4>
            </div>

            <form action="" method="post" enctype="multipart/form-data" id="form-complete">
                {{csrf_field()}}
                <div class="modal-body">
                    <textarea name="message" rows="3" class="form-control" placeholder="Enter an optional message" required></textarea>
                    <input type="file" multiple name="files[]">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary btn-sm "><i class="fa fa-send"></i> Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endpush
@push('scripts')
    <script src="/js/custom.js"></script>
@endpush
