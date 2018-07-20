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
                                <li><a href="{{route('jobs.category', $category->id)}}" class="category list-inline-item">{{$category->name}}</a></li>
                            @endforeach
                        </ol>
                        <div class="clearfix"></div>
                        <div class="m-md-top nowrap text-muted">
                            Posted <span class="ago" >{{\Carbon\Carbon::parse($job->created_at)->diffForHumans()}}</span> by {{auth()->user()->username}}
                        </div>
                    </section>

                    <section class="air-card-divider-sm">
                        <label>Description:</label>
                        <div class="job-description">
                            {!! $job->desc !!}
                        </div>
                    </section>
                    <section class="air-card-divider-sm">
                        <label>Instruction:</label>
                        <div class="job-description">
                            {!! $job->instructions !!}
                        </div>
                    </section>

                    <section class="air-card-divider-sm">
                        <ul class="job-features">
                            <li>
                                <strong><i class="fa fa-usd" aria-hidden="true"></i> {{$job->price}}</strong>
                                <small class="text-muted">Fixed-price</small>
                            </li>
                            <li>
                                <strong><i class="fa fa-eye"></i> {{$job->getViews()}}</strong>
                                <small class="text-muted">Viewed</small>
                            </li>
                            @if(isset($job->tag))
                            <li>
                                <strong><i class="fa fa-tag" aria-hidden="true"></i> <a href="{{route('jobs.index')}}?tag={{$job->tag->value}}" class="text-muted">{{$job->tag->value}}</a></strong>
                                <small class="text-muted">Tag</small>
                            </li>
                            @endif
                        </ul>
                    </section>
                    <section class="air-card-divider-sm">
                        <ul class="list-unstyled">
                            <li>
                                <strong class="m-sm-right">Job Status:</strong><span class="label label-default">{{$job->status}}</span>
                            </li>
                            <li>
                                <strong class="m-sm-right">Job Access:</strong><span>{{$job->access}}</span>
                            </li>
                            <li>
                                <strong class="m-sm-right">Ends:</strong><span><i class="fa fa-clock-o" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($job->end_date)->format('d M, Y H:i') }}</span>
                            </li>
                            <li>
                                <strong class="m-sm-right">Difficulty:</strong><span class="label label-default"> {{ $job->difficulty->name }}</span>
                            </li>
                            <li>
                                <strong class="m-sm-right">Time for work:</strong><span> {{ $job->time_for_work }} {{ $job->time_for_work == 1 ? 'hour' : 'hours' }}</span>
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
                                    @if(isset($job->application))
                                        <button id="{{$job->application->id}}" class="btn btn-primary btn-block job-app-status-btn" type="button">
                                            <i class="fa fa-briefcase"></i>
                                            Check Status
                                        </button>
                                    @else
                                        @if(\Carbon\Carbon::now() <= $job->end_date)
                                            <button id="{{$job->id}}" class="btn btn-primary btn-block apply-job-btn" type="button">
                                                <i class="fa fa-briefcase"></i>
                                                @lang('home.apply')
                                            </button>
                                        @else
                                            <button disabled id="{{$job->id}}" class="btn btn-primary btn-block apply-job-btn" type="button">
                                                <i class="fa fa-briefcase"></i>
                                                @lang('home.enddate')
                                            </button>
                                        @endif
                                    @endif

                                </div>
                                <form action="{{route('bookmark.store', $job)}}" method="post">
                                    {{csrf_field()}}
                                    <div class=" col-lg-12 col-sm-6 col-xs-6">
                                        <button class="btn btn-danger btn-block" type="submit">
                                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            {{!isset($job->bookmarkUser) ? 'Save Job' : 'Saved'}}
                                        </button>
                                    </div>
                                </form>
                                <div class=" col-lg-12 col-sm-6 col-xs-6">
                                    <button id="{{$job->id}}" data-title="{{$job->name}}" class="btn btn-success btn-block share-job-btn">
                                        <i class="fa fa-share" aria-hidden="true"></i> Share Job
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
@push('styles')
    <style>

        .p-0-top-bottom {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        .m-0-top-bottom {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        .air-card {
            position: relative;
            background-color: #fff;
            padding: 30px;
        }
        .air-card header, .air-card footer {
            margin: 0 -30px;
            padding: 0 30px;
        }
        .air-card header {
            border-bottom: 1px solid #E0E0E0;
        }
        .air-card section {
            border-bottom: 1px solid #E0E0E0;
            margin: 0 -30px;
            padding: 20px 30px;
        }
        .sands-category > li >  a, .file-list > li > a {
            color: #37A000;
            font-weight: 500;
        }
        .buttons  a.btn-primary:hover,
        .buttons  a.btn-primary:focus,
        .buttons  a.btn-primary:active,
        .buttons  a.btn-primary.active {
            color: #fff;
            background-color: #008329;
        }
        .sands-category {
            list-style: none;
            padding: 0;
        }
        .text-muted {
            color: #656565 !important;
        }
        .nowrap {
            white-space: nowrap;
        }
        .jd-card .content .job-description {
            overflow-wrap: break-word;
        }
        .jd-card .content ul.job-features {
            list-style: none;
            margin-right: -10px;
            display: flex;
            flex-wrap: wrap;
            padding-left: 0;
        }
        .jd-card .content section>*:last-child {
            margin-bottom: 0 !important;
        }
        .jd-card .content ul.job-features>li {
            width: 33.33%;
            position: relative;
            padding-right: 10px;
        }
        .jobdetails-tier-level-icon {
            speak: none;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            width: 20px;
            height: 20px;
            font-size: 13px;
            line-height: 20px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin-left: 10px;
            margin-right: 10px;
        }
        .jd-card .content ul.job-features>li>i {
            position: absolute;
            left: 0;
            margin-left: 0;
        }
        .jd-card .content ul.job-features>li>i.jobdetails-tier-level-icon {
            letter-spacing: -2px;
        }
        .jd-card .content ul.job-features>li strong {
            display: block;
        }
        @media (min-width: 992px){
            .jd-card>.row>aside {
                float: right;
                max-width: 30%;
            }
        }
        .jd-card .sidebar-actions {
            order: 1;
        }
        @media (min-width: 992px) {
            .jd-card .sidebar-actions {
                float: right;
                margin-bottom: 0;
                padding-bottom: 0;
            }
        }
        @media (min-width: 992px) {
            .jd-card .sidebar-actions .sidebar {
                padding: 30px 30px 0 0;
            }
        }
        @media (min-width: 992px) {
            .jd-card>.row>aside:first-of-type>.sidebar>section:first-child {
                border-top: 0;
                padding-top: 30px;
                padding-bottom: 30px;
            }
        }
        .jd-card .sidebar-actions .sidebar section:first-child {
            padding-top: 0;
        }
        .air-card section:last-child {
            border-bottom: none;
        }
        .air-card section {
            border-bottom: 1px solid #E0E0E0;
            margin: 0 -30px;
            padding: 20px 30px;
        }
        .jd-card .sidebar-actions .row.buttons {
            margin-left: -7.5px;
            margin-right: -7.5px;
        }
        .jd-card .sidebar-actions .btn {
            margin: 0 0 15px 0;
        }
        .air-card section .btn-primary {
            color: #fff;
            background-color: #37A000;
            border-color: transparent;
        }
        .o-tag-skill {
            cursor: pointer;
            font-weight: normal;
            margin: 0 6px 10px 0;

        }
        .m-xs-bottom {
            margin-bottom: 5px !important;
        }
        .o-tag-skill, .tokenizer-wrapper>.tokenizer-token-list {
            background-color: #E0E0E0;
            border-radius: 4px;
            color: #222;
            font-size: 12px;
            display: inline-block;
            cursor: default;
            padding: 5px 10px;
            line-height: 1;
        }
        .o-tag-skill:hover, .o-tag-skill:active {
            background-color: #008329;
            color: #fff;
        }
        .tag-list {
            display: block;
        }
        .m-sm-right {
            margin-right: 10px !important;
        }
        section.other-jobs a {
            color: #37A000;
        }
        section.other-jobs a.delete {
            color: red;
        }
        .delete-form {
            display: inline-block;
        }
        .delete-form button {
            border: none;
            background: no-repeat;
            color: red;
        }
        .delete-form button:hover {
            text-decoration: underline;
        }

    </style>
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

@endpush