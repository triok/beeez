@extends('layouts.app')

@section('content')
    <div class="col-sm-3 sidebar-offcanvas" role="navigation">
        <div id="sidebar">
            <div class="Categories">@lang('layout.categories')</div>

            <ul class="nav">
                @foreach(\App\Queries\CategoryQuery::onlyParent()->orderBy('cat_order','ASC')->get() as $cat)
                    <li>
                        <div style="display: flex; justify-content: space-between;">
                            <router-link to="/jobs/category/{{$cat->id}}">
                                @if(($locale = App::getLocale())=="ru")
                                    {{ucwords($cat->nameRu)}}
                                @else
                                    {{ucwords($cat->nameEu)}}
                                @endif
                            </router-link>

                            <i class="fa fa-plus" data-toggle="collapse" data-target="#navbarToggler{{$cat->id}}"
                               aria-controls="navbarToggler" aria-expanded="true" aria-label="Toggle navigation"
                               style="display: block;"></i>
                        </div>

                        @if(count($cat->subcategories) > 0)
                            <ul class="navbar-collapse collapse subcategory-ul" id="navbarToggler{{$cat->id}}" aria-expanded="true">
                                @foreach($cat->subcategories as $subcategory)
                                    <li class="subcategory-li">
                                        <router-link to="/jobs/category/{{$subcategory->id}}">
                                            @if(($locale = App::getLocale())=="ru")
                                                {{ucwords($subcategory->nameRu)}}
                                            @else
                                                {{ucwords($subcategory->nameEu)}}
                                            @endif
                                        </router-link>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-sm-9" id="main2">
        <h3>
            @if(isset($title))
                {{$title}}
            @else
                @lang('home.title')
            @endif
        </h3>

        @if(isset($category))
            <div class="alert bg-info small">{{ ucwords($category->nameRu) }}</div>
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

                        <td>{{ \Carbon\Carbon::parse($job->end_date)->format('d M, Y') }}
                            <b>{{ \Carbon\Carbon::parse($job->end_date)->format('H:i') }}</b></td>
                        <td>{{ $job->formattedPrice }}</td>
                        <td>
                            {{--{{dd($job->applications)}}--}}
                            @if(Auth::check())
                                @if(count($job->applications) > 0)
                                    @if(isset($job->application))
                                        <button data-id="{{$job->id}}"
                                                {!! $job->status == config('enums.jobs.statuses.IN_REVIEW') ? 'disabled' : '' !!} class="btn btn-success btn-sm btn-review">
                                            <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                            @lang('home.complete')
                                        </button>
                                    @else
                                        <button disabled class="btn btn-warning btn-sm "><i class="fa fa-history"
                                                                                            aria-hidden="true"></i> @lang('home.in_progress')
                                        </button>
                                    @endif
                                @else
                                    @if(\Carbon\Carbon::now() <= $job->end_date && $job->status == config('enums.jobs.statuses.OPEN'))
                                        <form action="{{route('jobs.apply', $job)}}" method="post">
                                            {{csrf_field()}}
                                            <button class="btn btn-default btn-sm" type="submit"><i
                                                        class="fa fa-briefcase"></i> @lang('home.apply') </button>
                                        </form>
                                    @elseif($job->status == config('enums.jobs.statuses.IN_PROGRESS') || $job->status == config('enums.jobs.statuses.IN_REVIEW'))
                                        <button disabled id="{{$job->id}}" class="btn btn-default btn-sm apply-job-btn">
                                            <i class="fa fa-briefcase"></i> @lang('home.in_progress') </button>
                                    @elseif($job->status == config('enums.jobs.statuses.COMPLETE'))
                                        <button disabled id="{{$job->id}}" class="btn btn-default btn-sm apply-job-btn">
                                            <i class="fa fa-briefcase"></i> @lang('home.complete') </button>
                                    @else
                                        <button disabled id="{{$job->id}}" class="btn btn-default btn-sm apply-job-btn">
                                            <i class="fa fa-briefcase"></i> @lang('home.enddate') </button>
                                    @endif
                                @endif
                            @else
                                <button id="{{$job->id}}" class="btn btn-default btn-sm apply-job-btn"><i
                                            class="fa fa-briefcase"></i> @lang('home.apply') </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-sm-12">{{$jobs->links()}}</div>
        </div>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
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

        <div class="modal fade" id="completeJobForm" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">You can add some comment or files.</h4>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data" id="form-complete">
                        {{csrf_field()}}
                        <div class="modal-body">
                            <textarea name="message" rows="3" class="form-control"
                                      placeholder="Enter an optional message" required></textarea>
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
