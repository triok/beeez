@extends('layouts.app')

@section('content')
    <div class="col-sm-3 sidebar-offcanvas category-nav" role="navigation">
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

                            <i class="fa fa-arrow-down" data-toggle="collapse" data-target="#navbarToggler{{$cat->id}}"
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

    <router-view></router-view>
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
