<div id="task-{{ isset($task_id) ? $task_id : 1 }}" class="tab-pane {{ !isset($task_id) ? 'fade in active' : '' }}">
    <h2>
        @if(!isset($task_id))
            @if(isset($job))
                <i class="fa fa-pencil"></i> {{$job->name}}
            @else
                <i class="fa fa-plus"></i> @lang('edit.newjob')
            @endif
        @endif
    </h2>

    <div class="row">
        <div class="form-group col-sm-8">
            <label data-toggle="tooltip" data-placement="left" title="@lang('edit.tooltip-name')">
                @lang('edit.name')
            </label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-name" : "name")
            @php($value = isset($job) ? $job->name : (isset($task_id) ? "Задание " . $task_id : ""))

            <input name="{{ $name }}"
                   onkeyup="$('.tab-{{ isset($task_id) ? $task_id : 1 }}-name').html($(this).val())"
                   value="{{ $value }}"
                   class="form-control"
                   required="required"
                   placeholder="Например: Придумать уникальный текст на тему &quot;Применение камня в интерьере&quot;">
        </div>

        <div class="form-group col-sm-4">
            <label>@lang('edit.status')</label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-status" : "status")
            <select name="{{ $name }}" id="status" class="form-control">
                @foreach(config('enums.jobs.statuses') as $status)
                    <option value="{{$status}}" {{isset($job) && $job->status == $status ? 'selected' : '' }}>{{$status}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label data-toggle="tooltip" data-placement="left" title="@lang('edit.tooltip-desc')">
            @lang('edit.desc')
        </label>

        @php($name = isset($task_id) ? "sub-" . $task_id . "-desc" : "desc")
        {!! Form::textarea($name, isset($job) ? $job->desc : '',['class'=>'editor1']) !!}
    </div>

    <div class="form-group">
        <label data-toggle="tooltip" data-placement="left" title="@lang('edit.tooltip-inst')">
            @lang('edit.instruction')
        </label>

        @php($name = isset($task_id) ? "sub-" . $task_id . "-instructions" : "instructions")
        {!! Form::textarea($name, isset($job) ? $job->instructions : '',['class'=>'editor2','id'=>'editor2']) !!}
    </div>

    <div class="form-group">
        <label for="access">@lang('edit.access')</label>
        <span class="label label-warning">@lang('edit.visible')</span>

        @php($name = isset($task_id) ? "sub-" . $task_id . "-access" : "access")
        {!! Form::textarea($name, isset($job) ? $job->access : '', ['class'=>'form-control', 'rows'=>'4']) !!}
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label>@lang('edit.price'), руб.</label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-price" : "price")
            {!! Form::input('input', $name, isset($job) ? $job->price: null, ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label>@lang('edit.difficulty')</label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-difficulty_level_id" : "difficulty_level_id")
            {!! Form::select($name, $_difficultyLevels, (isset($job) && $job->difficulty) ? $job->difficulty->id : 1,['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label>@lang('edit.enddate')</label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-end_date" : "end_date")
            <input name="{{ $name }}" class="form-control timepicker-actions" type='text'
                   value="{{ (isset($job) ? $job->end_date->format('d.m.Y H:i') : '') }}" required/>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label for="time_for_work">@lang('edit.timefor')</label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-time_for_work" : "time_for_work")
            {!! Form::select($name, array('1' => '1 hour', '2' => '2 hours', '3' => '3 hours'), isset($job) ? $job->time_for_work : 1,['class'=>'form-control', 'id' => 'time_for_work'] )!!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label for="user">@lang('edit.chooseuser')</label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-user" : "user")
            <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="{{ $name }}">
                <option selected value="">@lang('edit.anyone')</option>

                @foreach($usernames as $key => $username)
                    <option value="{{$key}}" {{isset($job) && $job->hasLogin($key) ? 'selected' : ''}}>{{$username}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label for="categories2">@lang('edit.category')</label>

            @php
                $category = null;
                $categoryParent = null;

                if(isset($job) && $job->jobCategories()) {
                    $category_id = $job->jobCategories()->first()->category_id;

                    $category = \App\Models\Jobs\Category::find($category_id);
                }
            @endphp

            @php($name = isset($task_id) ? "sub-" . $task_id . "-categories[]" : "categories[]")
            {!! Form::input('hidden', $name, ($category ? $category->id : ''), ['class'=>'form-control', 'id'=>'input-category-id']) !!}

            @php($name = isset($task_id) ? "sub-" . $task_id . "-category_name" : "category_name")
            {!! Form::input('input', $name, ($category ? (($category->parent ? $category->parent->nameEu . ' & ' : '') . $category->nameEu) : ''), ['class'=>'form-control input-category-name']) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="input-projects">@lang('edit.project')</label>

        @php($name = isset($task_id) ? "sub-" . $task_id . "-project_id" : "project_id")
        <select name="{{ $name }}" id="input-projects" class="form-control">
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

    <div class="form-group">
        <label for="input-tag">@lang('edit.cms')</label>

        @php($name = isset($task_id) ? "sub-" . $task_id . "-tag" : "tag")
        <select name="{{ $name }}" id="input-tag" class="form-control">
            <option value="" selected>@lang('edit.nocms')</option>

            @foreach(config('tags.tags') as $tag)
                <option value="{{$tag['value']}}" {{isset($job) && isset($job->tag) && $job->tag->value == $tag['value'] ? 'selected' : ''}}>{{$tag['title']}}</option>
            @endforeach
        </select>
    </div>

    @if(isset($task_id))
        <div class="form-group">
            <label for="sub-{{$task_id}}-files">Files:</label>

            <input type="file" multiple name="sub-{{$task_id}}-files[]" id="sub-{{$task_id}}-files">
        </div>
    @endif
</div>