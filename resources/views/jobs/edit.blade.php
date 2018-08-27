@extends('layouts.app')
@stack('styles')
@push('styles')
    <link href="/plugins/dropzone/dropzone.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plugins/bootstrap-select/bootstrap-select.min.css" />
@endpush
@section('content')
<div class="container-fluid">
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas"  role="navigation">
        <div id="sidebar"> 
            <div class="Categories">@lang('edit.create')</div>
            <div>

                <a href="javascript:void(0);" id="separate-link">@lang('edit.separate')</a>

            </div> 
        </div>
    </div>

<div class="col-sm-9" id="main">
    <div class="content-form">
        @if(isset($job))
            <h2><i class="fa fa-pencil"></i> {{$job->name}}</h2>
            {!! Form::model($job,['url'=>route('jobs.update',$job->id),'method'=>'patch']) !!}
        @else
            <h2><i class="fa fa-plus"></i> @lang('edit.newjob')</h2>
            {!! Form::open(['url'=>'/jobs', 'files' => true, 'enctype' => 'multipart/form-data']) !!}
        @endif
        <div class="row">
            <div class="col-sm-8">
                <label data-toggle="tooltip" data-placement="left" title="@lang('edit.tooltip-name')">@lang('edit.name')</label>
                {!! Form::text('name',isset($job) ? $job->name : '',['required'=>'required','class'=>'form-control','placeholder'=>'Например: Придумать уникальный текст на тему "Применение камня в интерьере"']) !!}
            </div>

            <div class="col-sm-4">
                <label>@lang('edit.status')</label>
{{--                {!! Form::select('status',array_keys(config('enums.jobs.statuses')), isset($job) ? array_search($job->status, array_values(config('enums.jobs.statuses'))) : 1,['class'=>'form-control']) !!}--}}
                <select name="status" id="status" class="form-control">
                    @foreach(config('enums.jobs.statuses') as $status)
                        <option value="{{$status}}" {{isset($job) && $job->status == $status ? 'selected' : '' }}>{{$status}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <label data-toggle="tooltip" data-placement="left" title="@lang('edit.tooltip-desc')">@lang('edit.desc')</label>
        {!! Form::textarea('desc', isset($job) ? $job->desc : '',['class'=>'editor1']) !!}
        <label data-toggle="tooltip" data-placement="left" title="@lang('edit.tooltip-inst')">@lang('edit.instruction')</label>
        {!! Form::textarea('instructions', isset($job) ? $job->instructions : '',['class'=>'editor2','id'=>'editor2']) !!}
        <br/>
        <div class="row">
            <div class="col-md-12">
                <label for="access">@lang('edit.access')</label>
                <span class="label label-warning">@lang('edit.visible')</span>
                {!! Form::textarea('access', isset($job) ? $job->access : '' ,['class'=>'form-control', 'rows'=>'4']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>@lang('edit.price')</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    {!! Form::input('input','price',isset($job) ? str_replace('$','111',$job->formattedPrice): null,['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="row">        
            <div class="col-md-4">
                <label>@lang('edit.difficulty')</label>
                {!! Form::select('difficulty_level_id',$_difficultyLevels, (isset($job) && $job->difficulty) ? $job->difficulty->id : 1,['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="row">    
            <div class="col-md-4">
                <label>@lang('edit.enddate')</label>

                {{--            <input type="datetime-local" name="end_date" value="{{ isset($job)?date('Y-m-d',strtotime($job->end_date)):null}}" required class="form-control">--}}
                {!! Form::input('datetime-local','end_date',isset($job) ? \Carbon\Carbon::parse($job->end_date)->format('d-m-Y H:i') : '',['required'=>'required','class'=>'form-control']) !!}
{{--                {!! Form::input('datetime-local','end_date', '2018-07-05T01:01',['required'=>'required','class'=>'form-control']) !!}--}}

            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label for="time_for_work">@lang('edit.timefor')</label>
                {!! Form::select('time_for_work', array('1' => '1 hour', '2' => '2 hours', '3' => '3 hours'), isset($job) ? $job->time_for_work : 1,['class'=>'form-control', 'id' => 'time_for_work'] )!!}
            </div>
        </div>
        <div class="row">        
            <div class="col-md-4">
                <label for="user">@lang('edit.chooseuser')</label>
                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="user">
                    <option selected value="">@lang('edit.anyone')</option>
                    @foreach($usernames as $key => $username)
                        <option value="{{$key}}" {{isset($job) && $job->hasLogin($key) ? 'selected' : ''}}>{{$username}}</option>
                    @endforeach
                </select>
            </div>
        </div>


<!--     <label>Skills:</label>
    <div class="row">
        @foreach($_skills as $skill)
            <div class="col-sm-3">
                {!! Form::checkbox('skills[]',$skill->id, isset($job) && $job->hasSkill($skill) ? 'checked' : '') !!}{{ucwords($skill->name)}}
            </div>
        @endforeach
    </div> -->

    <br/>
    <label>@lang('edit.category')</label>
    <div class="row">
        @foreach($_categories as $cat)
            <div class="col-sm-3">
                {!! Form::checkbox('categories[]',$cat->id, isset($job) && $job->hasCategory($cat) ? 'checked' : '',['style'=>'']) !!} {{$cat->nameEu}}
            </div>
        @endforeach
    </div>
    <br>

    <label>@lang('edit.project')</label>
    <div class="row">
        <div class="col-md-12">
            <select name="project_id" id="input-projects" class="form-control">
                <option value="">@lang('edit.noproject')</option>
                @foreach($projects as $project)
                    @if((isset($job) && $project->id == $job->project_id) || $project->id == old('project_id'))
                        <option selected value="{{ $project->id }}">{{ $project->name }}</option>
                    @else
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <br/>

    <label>@lang('edit.cms')</label>
    <div class="row">
        <div class="col-md-12">
            <select name="tag" id="tag" class="form-control">
                <option value="" selected>@lang('edit.nocms')</option>
                @foreach(config('tags.tags') as $tag)
                    <option value="{{$tag['value']}}" {{isset($job) && isset($job->tag) && $job->tag->value == $tag['value'] ? 'selected' : ''}}>{{$tag['title']}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br/>
    <div class="col-md-12">
        <div class="sub-tasks hide">
            @include('jobs.sub-job', ['sub_id' => 1])
            <div class="btn-toolbar" role="toolbar">
                <button type="button" class="btn btn-success btn-sm" id="taskAdd"><i class="fa fa-plus"></i> Add sub task</button>
            </div>
        </div>
    </div>
    <div class="" id="savesubmit">

            <button type="submit" class="btn btn-primary" id="submit" name="submit" value="submit">@lang('edit.submit')</button>
            <button type="submit" class="btn btn-primary" id="draft" name="draft" value="save">@lang('edit.save')</button>

    </div>

    {!! Form::close() !!}
    <div class="clearfix"><hr/></div>
    <div class="row file-upload" >
        <div class="col-md-12">
            {!! Form::open([ 'route' => ['files.upload'], 'files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'dropzone' ]) !!}
            <div>
                <h3>Upload Multiple Files By Click On Box</h3>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    </div>

</div>
</div>
@endsection
@include('partials.summer',['editor'=>'.editor1'])
@include('partials.tinymce',['editor'=>'.editor2'])
@push('scripts')
    <script src="/plugins/dropzone/dropzone.js" type="text/javascript"></script>
    <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="/js/custom.js"></script>
    <script>
        var myDropzone = Dropzone.options.dropzone = {
            maxFilesize: 5,
            addRemoveLinks: true,
            maxFiles: 10,
            parallelUploads: 1,
            //autoQueue: false,

            init:function() {
                this.on("addedfile", function(file) {
                    $.ajax({
                        type: 'POST',
                        url: "{{route('files.upload')}}",
                        data: {file: file.name, _token: "{{ csrf_token() }}"},
                        dataType: 'html',
                    });
                });
            },
        };
    </script>
@endpush