@extends('layouts.app')
@stack('styles')
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
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
            {!! Form::text('name','test',['required'=>'required','class'=>'form-control']) !!}
        </div>
        <div class="col-sm-4">
            <label>Status</label>
            {!! Form::select('status',['open'=>'Open','closed'=>'Closed'],1,['class'=>'form-control']) !!}
        </div>
    </div>
    <label>Description</label>
    {!! Form::textarea('desc','test description',['class'=>'editor1']) !!}
    <label>Instructions</label>
    <span class="label label-warning">Only visible to applicant after approval</span>
    {!! Form::textarea('instructions','test instruction',['class'=>'editor2','id'=>'editor2']) !!}
    <br/>
    <div class="row">
        <div class="col-md-12">
            <label for="access">Access</label>
            {!! Form::input('text','access','',['class'=>'form-control']) !!}
            {{--<input type="text" name="access" id="access" class="form-control">--}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <label>Price</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                {!! Form::input('input','price',isset($job)?str_replace('$','',$job->formattedPrice):999,['class'=>'form-control']) !!}
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
    <br/>

    <button class="btn btn-default btn-md" id="submit">Submit</button>
    {!! Form::close() !!}
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
    <script src="/plugins/dropzone.js" type="text/javascript"></script>
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
                // this.on("removedfile", function(file) {
                //
                // });
            },
        };
    </script>
@endpush