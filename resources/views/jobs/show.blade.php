@extends('layouts.app')

@section('content')
    {{--{{dd($job->tag)}}--}}
    <div class="jd-container standalone">
        <div class="jd-card air-card p-0-top-bottom m-0-top-bottom">
            <div class="row">
                <div class="content col-lg-8">
                    <header>
                        <h2>{{$job->name}}</h2>
                    </header>

                    <section class="air-card-divider-sm">
                        <ol class="sands-category list-inline">
                            @foreach($job->categories as $category)
                                <li><span>@lang('show.category')</span><a href="{{route('jobs.category', $category)}}" class="category list-inline-item"> {{$category->name}}</a></li>
                            @endforeach
                        </ol>
                        <div class="clearfix"></div>
                        <div class="m-md-top nowrap text-muted">
                            @lang('show.posted') <span class="ago" >{{\Carbon\Carbon::parse($job->created_at)->diffForHumans()}}</span> @lang('show.by') {{auth()->user()->username}}
                            (<span class="text-success">{{$job->user->rating_positive}}</span>/<span class="text-danger">{{$job->user->rating_negative}}</span>)
                        </div>
                        {!! $job->status == config('enums.jobs.statuses.IN_REVIEW') && isset($job->application) ? '<p class="label label-danger">Your task is under review</p>' : '' !!}

                    </section>

                    <section class="air-card-divider-sm">
                        <label>@lang('show.description')</label>
                        <div class="job-description">
                            {!! $job->desc !!}
                        </div>
                    </section>
                    <section class="air-card-divider-sm">
                        <label>@lang('show.instruction')</label>
                        <div class="job-description">
                            {!! $job->instructions !!}
                        </div>
                    </section>

                    <section class="air-card-divider-sm">
                        <ul class="job-features">
                            <li>
                                <strong><i class="fa fa-usd" aria-hidden="true"></i> {{$job->price}}</strong>
                                <small class="text-muted">@lang('show.fixedprice')</small>
                            </li>
                            <li>
                                <strong><i class="fa fa-eye"></i> {{$job->getViews()}}</strong>
                                <small class="text-muted">@lang('show.viewed')</small>
                            </li>
                            @if(isset($job->tag))
                            <li>
                                <strong><i class="fa fa-tag" aria-hidden="true"></i> <a href="{{route('jobs.index')}}?tag={{$job->tag->value}}" class="text-muted">{{$job->tag->value}}</a></strong>
                                <small class="text-muted">@lang('show.tag')</small>
                            </li>
                            @endif
                        </ul>
                    </section>
                    <section class="air-card-divider-sm">
                        <ul class="list-unstyled">
                            <li>
                                <strong class="m-sm-right">@lang('show.status')</strong><span class="label label-default">{{$job->status}}</span>
                            </li>
                            <li>
                                <strong class="m-sm-right">Job Access:</strong><span>{{$job->access}}</span>
                            </li>
                            <li>
                                <strong class="m-sm-right">Project:</strong>
                                @if($job->project)
                                    <span>{{$job->project->name}}</span>
                                @else
                                    <span>нет</span>
                                @endif
                            </li>
                            <li>
                                <strong class="m-sm-right">@lang('show.enddate')</strong><span><i class="fa fa-clock-o" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($job->end_date)->format('d M, Y H:i') }}</span>
                            </li>
                            <li>
                                @if(isset($job->difficulty))
                                <strong class="m-sm-right">@lang('show.difficulty')</strong><span class="label label-default"> {{ $job->difficulty->name }}</span>
                                @endif
                            </li>
                            <li>
                                <strong class="m-sm-right">@lang('show.timefor')</strong><span> {{ $job->time_for_work }} {{ $job->time_for_work == 1 ? 'hour' : 'hours' }}</span>
                            </li>
                        </ul>
                    </section>
                    <section class="air-card-divider-sm">
                        <h4>Skills and expertise</h4>
                        <div class="tag-list">
                            @forelse($job->skills as $skill)
                                <span  class="o-tag-skill">{{$skill->name}}</span>
                            @empty
                                <span class="label label-warning">None specified</span>
                            @endforelse
                        </div>
                    </section>
                    @if(count($job->files) > 0)
                    <section class="air-card-divider-sm">
                        <div class="m-lg-bottom">
                            <h4>Attachment</h4>
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

                </div>
                <aside class="sidebar-actions col-lg-4">
                    <div class="sidebar">
                        <section >
                            <div class="row buttons">
                                <div class="primary col-lg-12 col-sm-6 col-xs-6">
                                    @if(count($job->applications) > 0)
                                        @if($job->status != config('enums.jobs.statuses.CLOSED') && $job->status != config('enums.jobs.statuses.COMPLETE'))
                                            @if(isset($job->application))
                                                <button data-id="{{$job->id}}" {!! $job->status == config('enums.jobs.statuses.IN_REVIEW') ? 'disabled' : '' !!} class="btn btn-success btn-block btn-review">
                                                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                                    @lang('home.complete')
                                                </button>
                                            @else
                                                <button disabled class="btn btn-warning btn-block "><i class="fa fa-history" aria-hidden="true"></i> @lang('home.in_progress') </button>
                                            @endif
                                        @endif

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

                                    {{--@if(isset($job->application))--}}
                                        {{--<button id="{{$job->application->id}}" class="btn btn-primary btn-block job-app-status-btn" type="button">--}}
                                            {{--<i class="fa fa-briefcase"></i>--}}
                                            {{--Check Status--}}
                                        {{--</button>--}}
                                        {{--<button disabled class="btn btn-primary btn-block" type="button">--}}
                                            {{--<i class="fa fa-history" aria-hidden="true"></i>--}}
                                            {{--@lang('home.in_progress')--}}
                                        {{--</button>--}}

                                    {{--@else--}}
                                        {{--@if(\Carbon\Carbon::now() <= $job->end_date)--}}
                                            {{--<button id="{{$job->id}}" class="btn btn-primary btn-block apply-job-btn" type="button">--}}
                                                {{--<i class="fa fa-briefcase"></i>--}}
                                                {{--@lang('home.apply')--}}
                                            {{--</button>--}}
                                        {{--@else--}}
                                            {{--<button disabled id="{{$job->id}}" class="btn btn-primary btn-block apply-job-btn" type="button">--}}
                                                {{--<i class="fa fa-briefcase"></i>--}}
                                                {{--@lang('home.enddate')--}}
                                            {{--</button>--}}
                                        {{--@endif--}}
                                    {{--@endif--}}

                                </div>
                                <form action="{{route('bookmark.store', $job)}}" method="post">
                                    {{csrf_field()}}
                                    <div class=" col-lg-12 col-sm-6 col-xs-6">
                                        <button class="btn btn-info btn-block" type="submit">
                                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            <?php if (!isset($job->bookmarkUser)) { ?>
                                                @lang('show.save')
                                            <?php } else { ?> 
                                                @lang('show.saved')                                                
                                            <?php } ?>
                                        </button>
                                    </div>
                                </form>
                                <div class="col-lg-12 col-sm-6 col-xs-6">
                                    <button id="{{$job->id}}" data-title="{{$job->name}}" class="btn btn-success btn-block share-job-btn">
                                        <i class="fa fa-share" aria-hidden="true"></i> @lang('show.share')
                                    </button>
                                </div>
                                <div class="col-lg-12 col-sm-6 col-xs-6">
                                    <button id="{{$job->id}}" data-title="{{$job->name}}" class="btn btn-danger btn-block complain-job-btn">
                                        <i class="fa fa-warning" aria-hidden="true"></i> @lang('show.complain')
                                    </button>
                                </div>
                            </div>
                        </section>
                    </div>
                </aside>

            </div>
        </div>


        <div class="air-card p-0-top-bottom">
            <header>
                <h2>Related jobs</h2>
            </header>
            <div>
                <section class="other-jobs">
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

                        @if($job->jobs()->count() <= 0 && !isset($parents))
                            <li><div class="alert alert-danger">No related tasks</div></li>
                        @endif
                    </ul>
                </section>
            </div>
        </div>
        <div class="air-card p-0-top-bottom">
            <div class="form-container">
                <h4>Comment:</h4>
                <form action="{{route('comments.store')}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="parent" id="parent" value="">
                    <input type="hidden" name="job" value="{{$job->id}}">
                    <textarea name="body" id="body" required rows="3" class="form-control"></textarea>
                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Send</button>
                </form>
            </div>
            <div class="comments">
                <h2 style="border-bottom: 1px solid rgba(34, 36, 38, .15);">Comments ({{$job->commentCount()}})</h2>
                <ul class="media-list">
                    @forelse($job->comments as $comment)
                        @include('jobs.comment', ['tag' => 'li'])
                    @empty
                        <li class="media">
                            <div class="alert alert-danger">No comments found!</div>
                        </li>
                    @endforelse
                </ul>
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