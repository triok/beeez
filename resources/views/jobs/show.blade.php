@extends('layouts.app')

@section('content')
<div class="container-fluid" id="main">
    <div class="jd-container standalone">
        <div class="jd-card air-card p-0-top-bottom m-0-top-bottom">
            <div class="row">
                <div class="content col-lg-7">
                    <div class="base-wrapper">
                    <header>
                        <div>
                            <h2>{{$job->name}}</h2>
                            @if(auth()->id() == $job->user_id && $job->project)
                            <div class="m-md-top nowrap text-muted">
                                <p>
                                    <span>Проект</span>
                                    <a href="{{ route('projects.show', $job->project) }}">
                                        <span class="username">{{ $job->project->name }}</span>
                                    </a>
                                </p>
                            </div>
                            @endif

                            @if($jobTeam)
                                <div class="m-md-top nowrap text-muted">
                                    <p>
                                        <span>Проект в команде <a href="{{ route('teams.show', $jobTeam) }}">{{ $jobTeam->name }}</a>: </span>
                                        <a href="{{ route('projects.show', $job->teamProject) }}">
                                            <span class="username">{{ $job->teamProject->name }}</span>
                                        </a>
                                        <a href="{{ route('jobs.editProject', $job) }}">
                                            <i class="fa fa-pencil" title="Изменить"></i>
                                        </a>
                                    </p>
                                </div>
                            @endif                            
                        </div>
                        <div>
                            @if($job->status != config('enums.jobs.statuses.CLOSED') && $job->status != config('enums.jobs.statuses.COMPLETE'))
                            <form action="{{route('bookmark.store', $job)}}" method="post" style="display: inline-flex;">
                                {{csrf_field()}}
                                <button class="btn btn-info" type="submit" title="{{ trans('show.save') }}">
                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                    <?php if (isset($job->bookmarkUser)) { ?>
                                        @lang('show.saved')  
                                    <?php }  ?>
                                </button>
                            </form>
                            <button id="{{$job->id}}" data-title="{{$job->name}}" class="btn btn-success share-job-btn" title="{{ trans('show.share') }}">
                                <i class="fa fa-share" aria-hidden="true"></i> 
                            </button>
                            @endif
                            <button id="{{$job->id}}" data-title="{{$job->name}}" class="btn btn-danger complain-job-btn" title="{{ trans('show.complain') }}">
                                <i class="fa fa-warning" aria-hidden="true"></i> 
                            </button>
                                                    
                        </div>
                    </header>

                    <section class="air-card-divider-sm header-section">
                        <div class="sands-category list-inline">
                            @foreach($job->categories as $category)
                                <li><span>@lang('show.category')</span> <a href="{{route('jobs.category', $category)}}" class="category list-inline-item">{{$category->nameRu}}</a></li>
                            @endforeach
                        </div>
                        <div class="clearfix"></div>
                        <div class="m-md-top nowrap text-muted">
                            @lang('show.posted') <span class="ago" ><strong>{{\Carbon\Carbon::parse($job->created_at)->diffForHumans()}}</strong></span> @lang('show.by') <a href="{{route('peoples.show', $job->user)}}"><span class="username">{{ $job->user->name }}</span></a>
                             (<span class="text-success">{{$job->user->rating_positive}}</span>/<span class="text-danger">{{$job->user->rating_negative}}</span>)
                        </div>
                        {!! $job->status == config('enums.jobs.statuses.IN_REVIEW') && isset($job->application) ? '<p class="label label-danger">Your task is under review</p>' : '' !!}

                        <div class="m-md-top nowrap text-muted">
                            <span>@lang('show.viewed')</span>
                            <strong><i class="fa fa-eye"></i> {{$job->getViews()}}</strong>
                        </div>

                        @if($application)
                            <div class="m-md-top nowrap text-muted">
                                <span>@lang('show.performer') </span>
                                <a href="{{ route('peoples.show', $application) }}">
                                    <span class="username">{{ $application->name }}</span>
                                </a>
                            </div>
                        @endif




                    </section>


                    <section class="air-card-divider-sm">
                        <ul class="job-features">
                            <li data-toggle="tooltip" data-placement="left" title="@lang('show.price')">

                                <span>@lang('show.budget')</span><strong> {{$job->formattedPrice}}</strong>

                            </li>
 
                        </ul>
                    </section>

                    <section class="air-card-divider-sm">
                        <label data-toggle="tooltip" data-placement="left" title="@lang('show.tooltip-desc')">@lang('show.description')</label>
                        <div class="job-description">
                            {!! $job->desc !!}
                        </div>
                    </section>
<!--                     <section class="air-card-divider-sm">
                        <label>@lang('show.instruction')</label>
                        <div class="job-description">
                            {!! $job->instructions !!}
                        </div>
                    </section> -->

                    <section class="air-card-divider-sm">
                        <ul class="list-unstyled">
                            <li>
                                <strong class="m-sm-right">@lang('show.timefor')</strong><span> {{ $time->value }}</span>
                            </li>
                            <li>
                                <strong class="m-sm-right">@lang('show.enddate')</strong><span class="enddate"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($job->end_date)->format('d M, Y H:i') }}</span>
                            </li>
                            <li>
                                <strong class="m-sm-right">@lang('show.status')</strong>
                                    @if($job->status == config('enums.jobs.statuses.OPEN'))
                                        <span class="label label-success">{{$job->status}}</span>
                                    @else
                                        <span class="label label-default">{{$job->status}}</span>
                                    @endif
                            </li>
<!--                             <li>
                                <strong class="m-sm-right">Job Access:</strong><span>{{$job->access}}</span>
                            </li>
                            <li>
                                <strong class="m-sm-right">Project:</strong>
                                @if($job->project)
                                    <span>{{$job->project->name}}</span>
                                @else
                                    <span>нет</span>
                                @endif
                            </li> -->

<!--                             <li>
                                @if(isset($job->difficulty))
                                <strong class="m-sm-right">@lang('show.difficulty')</strong><span class="label label-default"> {{ $job->difficulty->name }}</span>
                                @endif
                            </li> -->

                        </ul>
                    </section>
                    @if(count($job->skills) > 0)
                    <section class="air-card-divider-sm">
                        <label>@lang('show.skills')</label>
                        <div class="tag-list">
                            @foreach($job->skills as $skill)
                                <span  class="o-tag-skill">{{$skill->name}}</span>
                            @endforeach
                        </div>
                    </section>
                    @endif
                    @if(isset($job->tag))
                    <section class="air-card-divider-sm">
                        <label>@lang('show.cms')</label>
                        <span  class="o-tag-skill">{{$job->tag->value}}</span>
                    </section>    
                    @endif                                    
                    @if(count($job->files) > 0)
                    <section class="air-card-divider-sm">
                        <div class="m-lg-bottom">
                            <label>@lang('show.attachments')</label>
                            <ul class="list-unstyled file-list">
                                @foreach($job->files as $file)
                                <li>
                                    <span><i class="fa fa-paperclip"></i></span>
                                    <a href="{{route('file.upload', $file)}}">{{$file->original_name}} ({{number_format($file->size / 1024, 2, '.', '')}} KB)</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>
                    @endif
                    @if(count($job->jobs) > 0)
                    <section class="air-card-divider-sm">
                        <label>@lang('show.related')</label>
                        <ul class="list-unstyled" >
                            @foreach($job->jobs as $subJob)
                            <li>
                                <strong><a href="{{route('jobs.show', $subJob)}}"  target="_self">{{$subJob->name}}</a> </strong>
                            </li>
                            @endforeach

                            @if(isset($job->parent_id))
                                @php $parents = \App\Models\Jobs\Job::query()->where('parent_id',$job->parent_id )->get(); @endphp
                                @if(isset($job->parent))
                                    <li>
                                        <strong><a href="{{route('jobs.show', $job->parent)}}" target="_self">{{$job->parent->name}}</a> </strong>
                                    </li>
                                @endif
                                @foreach($parents as $parentJob)
                                    @continue($parentJob->id == $job->id)
                                    <li>
                                        <strong><a href="{{route('jobs.show', $parentJob)}}"  target="_self">{{$parentJob->name}}</a> </strong>
                                    </li>
                                @endforeach
                            @endif

                        </ul>                        
                    </section>
                    @endif
                    <section class="air-card-divider-sm">
                        @if($job->applications()->count())
<!--                             <div class="air-card p-0-top-bottom">
                                <div class="comments">
                                    <h2>@lang('show.questions') ({{$job->commentCount()}})</h2>
                                    <ul class="media-list">
                                        @forelse($job->comments as $comment)
                                            @include('jobs.comment', ['tag' => 'li'])
                                        @empty
                                            <li class="media">
                                                <div class="alert alert-warning">@lang('show.noquestions')</div>
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="form-container">
                                    <h2>@lang('show.question')</h2>
                                    <form action="{{route('comments.store')}}" method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="parent" id="parent" value="">
                                        <input type="hidden" name="job" value="{{$job->id}}">
                                        <textarea name="body" id="body" required rows="3" class="form-control"></textarea>
                                        <button type="submit" class="btn btn-info" style="margin-top: 10px;">@lang('show.send')</button>
                                    </form>
                                </div>
                            </div> -->
                        @endif
                    </section>

                </div>
                <div class="base-wrapper">@include('jobs.proposals')</div>                
                </div>
                <div class="col-lg-5">
                    <div class="sidebar">
                        <section >
                            <div class="row buttons">
                                <div class="primary col-lg-12 col-sm-6 col-xs-6">
                                    @if($job->applications)
<!--                                         @if($job->status != config('enums.jobs.statuses.CLOSED') && $job->status != config('enums.jobs.statuses.COMPLETE'))
                                            @if(isset($job->application))
                                                <button data-id="{{$job->id}}" {!! $job->status == config('enums.jobs.statuses.IN_REVIEW') ? 'disabled' : '' !!} class="btn btn-success btn-block btn-review">
                                                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                                    @lang('home.complete')
                                                </button>
                                            @else
                                                <button disabled class="btn btn-warning btn-block "><i class="fa fa-history" aria-hidden="true"></i> @lang('home.in_progress') </button>
                                            @endif
                                        @endif -->

                                        @if (isset($job->application) && auth()->user()->id == $job->application->user_id)

                                            @if ($job->status == config('enums.jobs.statuses.COMPLETE'))
                                                <button data-id="{{$job->id}}" class="btn btn-primary btn-block btn-rating" type="button">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    @lang('home.add_rating')
                                                </button>
                                            @endif
                                        @endif

                                         @if (auth()->user()->id == $job->user_id)
                                            @if ($job->status == config('enums.jobs.statuses.IN_REVIEW'))
                                                <button data-id="{{$job->id}}" class="btn btn-primary btn-block btn-rating" type="button">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    @lang('home.complete')
                                                </button>
                                            @endif


                                        @endif

                                    @else
                                        @if(\Carbon\Carbon::now() <= $job->end_date)
                                            <form action="{{route('jobs.apply', $job)}}" method="post">
                                                {{csrf_field()}}
                                                <button class="btn btn-default btn-block" type="submit"><i class="fa fa-briefcase"></i> @lang('home.apply') </button>
                                            </form>
                                        @else
                                            <button disabled id="{{$job->id}}" class="btn btn-default btn-block apply-job-btn"><i class="fa fa-briefcase"></i> @lang('home.enddate') </button>
                                        @endif
                                    @endif

 
                                </div>


                            </div>
                        </section>
                    </div>

                    @if($job->applications()->count())
                        <div class="base-wrapper">
                            @if (isset($application))
                                <form action="{{route('job.notify', $job)}}" method="post">
                                    {{csrf_field()}}
                                    <input type="hidden" name="user_id" value="{{ $application->id }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-refresh" aria-hidden="true"></i> @lang('show.report-request')
                                    </button>
                                </form>
                            @endif                        
                            @include('jobs.reports')
                        </div>
                    @endif
                    
                </div>

            </div>
        </div>


    </div>
</div>

@endsection
@push('scripts')
    <script src="/js/custom.js"></script>
@endpush

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

    <div class="modal fade" id="complainJobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Complain <span></span> Job</h4>
                </div>

                {!! Form::open(['url'=>'','class'=>'complain-job-form']) !!}
                <div class="modal-body">
                    {!! Form::hidden('job_id') !!}
                    <br/>
                    {!! Form::textarea('message',null,['class'=>'form-control','rows'=>3,'placeholder'=>'Enter your message']) !!}
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

    <div class="modal fade" id="ratingJobForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">You can add some comment.</h4>
                </div>

                <form action="" method="post" enctype="multipart/form-data" id="form-complete">
                    {{csrf_field()}}
                    {!! Form::hidden('job_id') !!}
                    <div class="modal-body">
                        <textarea name="message" rows="3" class="form-control" placeholder="Enter an optional message" required></textarea>

                        <div class="radio">
                            <label>
                                <input type="radio" name="rating" id="input-rating-1" value="positive" checked>
                                Positive
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="rating" id="input-rating-2" value="negative">
                                Negative
                            </label>
                        </div>
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