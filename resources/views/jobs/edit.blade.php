@extends('layouts.app')
@stack('styles')
@push('styles')
    <link href="/plugins/dropzone/dropzone.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plugins/bootstrap-select/bootstrap-select.min.css" />
    <link rel="stylesheet" href="/css/custom.css" />
    <link rel="stylesheet" href="/css/datepicker.min.css" />
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
                <label>@lang('edit.price'), руб.</label>
                <div class="input-group">
                    {!! Form::input('input','price',isset($job) ? $job->price: null,['class'=>'form-control']) !!}
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
                <input name="end_date" class="form-control" type='text' id='timepicker-actions-exmpl'
                       value="{{ (isset($job) ? $job->end_date->format('d.m.Y H:i') : '') }}" required />
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
            <div class="row">
                <div class="col-md-4">
                    <label for="categories2">@lang('edit.category')</label>

                    @php
                    $category = null;
                    $categoryParent = null;

                    if(isset($job) && $job->jobCategories()) {
                        $category_id = $job->jobCategories()->first()->category_id;

                        $category = \App\Models\Jobs\Category::find($category_id);
                    }
                    @endphp

                    {!! Form::input('hidden', 'categories[]', ($category ? $category->id : ''), ['class'=>'form-control', 'id'=>'input-category-id']) !!}
                    {!! Form::input('input', 'category_name', ($category ? (($category->parent ? $category->parent->nameEu . ' & ' : '') . $category->nameEu) : ''), ['class'=>'form-control', 'id'=>'input-category-name']) !!}
                </div>
            </div>
            <br>


    <label>@lang('edit.project')</label>
    <div class="row">
        <div class="col-md-12">
            <select name="project_id" id="input-projects" class="form-control">
                <option value="">@lang('edit.noproject')</option>
                @foreach($projects as $project)
                    @if((isset($job) && $project->id == $job->project_id) || $project->id == old('project_id') || $project->id == request('project_id'))
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

@push('modals')
    <div class="modal fade" id="modal-categories" tabindex="-1" role="dialog" aria-labelledby="modalCategoriesLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="title">
                        Category selection
                    </h4>
                </div>

                <div class="modal-body" style="overflow-y: scroll;height: 500px;">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="list-group">
                                @foreach($_categories as $cat)
                                    <a href="#"
                                       onclick="setCategory('{{ $cat->id }}', '{{ $cat->nameEu }}')"
                                       onmouseover="showSubCategories('{{ $cat->id }}')"
                                       class="list-group-item {{isset($job) && $job->hasCategory($cat, true) ? 'active' : ''}}">

                                        <span class="badge">&gt;</span>
                                        {{$cat->nameEu}}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        @foreach($_categories as $cat)
                            <div class="col-xs-6 subcategories"
                                 style="{{(isset($job) && $job->hasCategory($cat, true)) ? '' : 'display: none;'}}"
                                 id="subcategories-{{ $cat->id }}">

                                <div class="list-group">
                                    @foreach($cat->subcategories as $subcat)
                                        <a href="#"
                                           onclick="setCategory('{{ $subcat->id }}', '{{ $cat->nameEu . " & " . $subcat->nameEu }}')"
                                           class="list-group-item {{(isset($job) && $job->hasCategory($subcat)) ? 'active' : ''}}">
                                            {{$subcat->nameEu}}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script src="/plugins/dropzone/dropzone.js" type="text/javascript"></script>
    <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="/js/custom.js"></script>
    <script src="/js/datepicker.min.js"></script>
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
    <script>
        // Зададим стартовую дату
        var start = new Date(),
            prevDay,
            startHours = 9;

        // 09:00
        start.setHours(9);
        start.setMinutes(0);

        // Если сегодня суббота или воскресенье - 10:00
        if ([6,0].indexOf(start.getDay()) != -1) {
            start.setHours(10);
            startHours = 10
        }

        $('#timepicker-actions-exmpl').datepicker({
            timepicker: true,
            startDate: start,
            minHours: startHours,
            maxHours: 24,
            onSelect: function(fd, d, picker) {
                // Ничего не делаем если выделение было снято
                if (!d) return;

                var day = d.getDay();

                // Обновляем состояние календаря только если была изменена дата
                if (prevDay != undefined && prevDay == day) return;

                prevDay = day;

                picker.update({
                    minHours: 0,
                    maxHours: 24
                })
            }
        })
    </script>
@endpush