{{--{{dd(config('tags.tags'))}}--}}
@extends('layouts.app')
@stack('styles')
@push('styles')
    <link href="/plugins/dropzone/dropzone.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plugins/bootstrap-select/bootstrap-select.min.css" />
@endpush
@section('content')

    <div class="content-form">
        <div class="text-right">
            @if(isset($job))
                <a href="/jobs/create" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> </a>
            @endif
            <a href="/jobsAdmin" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> </a>
        </div>

        @if(isset($job))
            <h2><i class="fa fa-pencil"></i> {{$job->name}}</h2>
            {!! Form::model($job,['url'=>route('jobs.update',$job->id),'method'=>'patch']) !!}
        @else
            <h2><i class="fa fa-plus"></i> New Job</h2>
            {!! Form::open(['url'=>'/jobs', 'files' => true]) !!}
        @endif
        <div class="row">
            <div class="col-sm-8">
                <label>Name</label>
                {!! Form::text('name','',['required'=>'required','class'=>'form-control']) !!}
            </div>
            <div class="col-sm-4">
                <label>Status</label>
                {!! Form::select('status',['open'=>'Open','closed'=>'Closed'],1,['class'=>'form-control']) !!}
            </div>
        </div>
        <label>Description</label>
        {!! Form::textarea('desc','',['class'=>'editor1']) !!}
        <label>Instructions</label>
        <span class="label label-warning">Only visible to applicant after approval</span>
        {!! Form::textarea('instructions','',['class'=>'editor2','id'=>'editor2']) !!}
        <br/>
        <div class="row">
            <div class="col-md-12">
                <label for="access">Access</label>
                {!! Form::input('text','access','',['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Price</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    {!! Form::input('input','price',isset($job)?str_replace('$','',$job->formattedPrice):null,['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4">
                <label>Difficulty level</label>
                {!! Form::select('difficulty_level_id',$difficultyLevels,null,['class'=>'form-control']) !!}
            </div>
            <div class="col-md-4">
                <label>End date</label>
                {{--            <input type="datetime-local" name="end_date" value="{{ isset($job)?date('Y-m-d',strtotime($job->end_date)):null}}" required class="form-control">--}}
                {!! Form::input('datetime-local','end_date','',['required'=>'required','class'=>'form-control']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label for="time_for_work">Time for work</label>
                {!! Form::select('time_for_work', array('1' => '1 hour', '2' => '2 hours', '3' => '3 hours'), 1,['class'=>'form-control', 'id' => 'time_for_work'] )!!}
            </div>

            <div class="col-md-4">
                <label for="user">Choose user</label>
                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="user">
                    <option selected value="">For anyone</option>
                    @foreach($usernames as $username)
                        <option value="{{$username}}">{{$username}}</option>
                    @endforeach
                </select>
            </div>
        </div>


    <label>Skills:</label>
    <div class="row">
        @foreach(\App\Models\Jobs\Skills::get() as $skill)
            <div class="col-sm-3">
                {!! Form::checkbox('skills[]',$skill->id) !!}{{ucwords($skill->name)}}
            </div>
        @endforeach
    </div>

    <br/>
    <label>Categories</label>
    <div class="row">
        @foreach($categories as $cat)
            <div class="col-sm-3">
                {!! Form::checkbox('categories[]',$cat->id,null,['style'=>'']) !!} {{$cat->name}}
            </div>
        @endforeach
    </div>
    <br>
    <label>Choose CMS</label>
    <div class="row">
        <div class="col-md-12">
            <select name="tag" id="tag" class="form-control">
                <option value="" selected>I do not use CMS</option>
                @foreach(config('tags.tags') as $tag)
                    <option value="{{$tag['value']}}">{{$tag['title']}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br/>
        <div class="btn-toolbar" id="savesubmit">
            <div class="btn-group btn-group-lg">
                <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                <button type="button" class="btn btn-primary" id="save">Save</button>
            </div>
        </div>

    {!! Form::close() !!}
    {{--</form>--}}
    <div class="clearfix"><hr/></div>
    <div class="row">
        <div class="col-md-12">
            {!! Form::open([ 'route' => ['files.upload'], 'files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'dropzone' ]) !!}
            <div>
                <h3>Upload Multiple Files By Click On Box</h3>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    </div>


@endsection
@include('partials.summer',['editor'=>'.editor1'])
@include('partials.tinymce',['editor'=>'.editor2'])
@push('scripts')
    <script src="/plugins/dropzone/dropzone.js" type="text/javascript"></script>
    <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script type="text/javascript">

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