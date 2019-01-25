<div id="task-{{ isset($task_id) ? $task_id : 1 }}" class="tab-pane {{ !isset($task_id) ? 'fade in active' : '' }} job-form">
    <h2>
        @if(!isset($task_id))
            @if(isset($job))
                <i class="fa fa-pencil"></i> {{$job->name}}
            @else

            @endif
        @endif
    </h2>

    <div class="row job-row">
        <div class="form-group col-sm-8">
            <label data-toggle="tooltip" data-placement="left" title="@lang('edit.tooltip-name')">
                @lang('edit.name')<span class="required">*</span>
            </label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-name" : "name")
            @php($value = isset($job) ? $job->name : (isset($task_id) ? "Задание " . $task_id : ""))

            <input name="{{ $name }}"
                   onkeyup="$('.tab-{{ isset($task_id) ? $task_id : 1 }}-name').html(truncate($(this).val(), 20))"
                   value="{{ $value }}"
                   class="form-control"
                   required="required"
                   placeholder="@lang('edit.placeholder-name')">
            <small>@lang('edit.required')</small>
        </div>

        <div class="form-group col-sm-4">
            <label>@lang('edit.status')</label><i class="fa fa-question-circle-o pull-right" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="@lang('edit.tooltip-status')"></i>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-status" : "status")
            <select name="{{ $name }}" id="status" class="form-control">
                @foreach(config('enums.jobs.statuses') as $status)
                    <option value="{{$status}}" {{isset($job) && $job->status == $status ? 'selected' : '' }}>{{$status}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group job-form-group">
        <label data-toggle="tooltip" data-placement="left" title="@lang('edit.tooltip-desc')">
            @lang('edit.desc')<span class="required">*</span>
        </label>

        @php($name = isset($task_id) ? "sub-" . $task_id . "-desc" : "desc")
        {!! Form::textarea($name, isset($job) ? $job->desc : '',['class'=>'editor1']) !!}
        <small>@lang('edit.required')</small>
    </div>

<!--     <div class="form-group">
        <label data-toggle="tooltip" data-placement="left" title="@lang('edit.tooltip-inst')">
            @lang('edit.instruction')
        </label>

        @php($name = isset($task_id) ? "sub-" . $task_id . "-instructions" : "instructions")
        {!! Form::textarea($name, isset($job) ? $job->instructions : '',['class'=>'editor2','id'=>'editor2']) !!}
    </div> -->

    <div class="row job-row">
        <div class="form-group col-md-4">
            <label>@lang('edit.price'), руб.<span class="required">*</span></label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-price" : "price")
            {!! Form::input('input', $name, isset($job) ? $job->price: null, ['class'=>'form-control', 'name' => 'price']) !!}
            <small>@lang('edit.required')</small>
        </div>
        <div class="form-group col-md-4">
            <label>@lang('edit.enddate')<span class="required">*</span></label>

            @php($name = isset($task_id) ? "sub-" . $task_id . "-end_date" : "end_date")
            <input name="{{ $name }}" class="form-control timepicker-actions" type='text'
                   value="{{ ((isset($job) && $job->end_date) ? $job->end_date->format('d.m.Y H:i') : '') }}" autocomplete="off"/>
            <small>@lang('edit.required')</small>
        </div>

        @php($application = (isset($job) && $application = $job->applications()->first()) ? $application : 0)
        <div class="form-group col-md-4 search" style="margin: 0 0 15px 0;">
            <label>@lang('edit.chooseuser')</label>
            @php($name = isset($task_id) ? "sub-" . $task_id . "-user" : "user")
            <input type="hidden" name="{{ $name }}" class="form-control search-user" value="{{ $application ? $application->user->id : ''}}">
            <input type="text" class="form-control search-input" placeholder="@lang('edit.anyone')"
                   value="{{ $application ? $application->user->name : ''}}">
            <ul class="result" style="top: 65px;"></ul>
        </div>
    </div>
    <div class="row job-row">
        <div class="form-group col-md-4">
            <label for="categories2">@lang('edit.category')<span class="required">*</span></label>

            @php
                $category = null;
                $categoryParent = null;

                if(isset($job) && $job->jobCategories() && $j = $job->jobCategories()->first()) {
                    $category_id = $j->category_id;

                    $category = \App\Models\Jobs\Category::find($category_id);
                }
            @endphp

            @php($name = isset($task_id) ? "sub-" . $task_id . "-categories[]" : "categories[]")
            {!! Form::input('hidden', $name, ($category ? $category->id : ''), ['class'=>'form-control', 'id'=>'input-category-id']) !!}

            @php($name = isset($task_id) ? "sub-" . $task_id . "-category_name" : "category_name")
            {!! Form::input('input', $name, ($category ? (($category->parent ? $category->parent->nameEu . ' & ' : '') . $category->nameEu) : ''), ['class'=>'form-control input-category-name']) !!}
            <small>@lang('edit.required')</small>
        </div>
    </div>


    <div class="job-row">
        <div class="job-accordion" id="accordionEx78{{ $name }}" role="tablist" aria-multiselectable="true">
            <div class="job-card">
                <div class="card-header" role="tab" id="headingUnfiled{{ $name }}">
                    <a data-toggle="collapse" data-parent="#accordionEx78" href="#collapseUnfiled{{ $name }}" aria-expanded="true"
                        aria-controls="collapseUnfiled{{ $name }}">
                        <label>
                            <span>@lang('edit.additional-fields')</span>
                            <i class="fa fa-angle-down rotate-icon"></i>
                        </label>
                    </a>
                </div>
                <div id="collapseUnfiled{{ $name }}" class="collapse" role="tabpanel" aria-labelledby="headingUnfiled{{ $name }}" data-parent="#accordionEx78{{ $name }}">
                    <div class="card-body">
                        <div class="form-group job-form-group">
                            <label for="access">@lang('edit.access')</label><i class="fa fa-question-circle-o pull-right" aria-hidden="true"></i>
                            <span class="label label-warning">@lang('edit.visible')</span>

                            @php($name = isset($task_id) ? "sub-" . $task_id . "-access" : "access")
                            {!! Form::textarea($name, isset($job) ? $job->access : '', ['class'=>'form-control', 'rows'=>'4']) !!}
                        </div>
                        <div class="row job-row">
                            <div class="form-group col-md-4">
                                <label>@lang('edit.difficulty')</label><i class="fa fa-question-circle-o pull-right" aria-hidden="true"></i>

                                @php($name = isset($task_id) ? "sub-" . $task_id . "-difficulty_level_id" : "difficulty_level_id")
                                {!! Form::select($name, $_difficultyLevels, (isset($job) && $job->difficulty) ? $job->difficulty->id : 1,['class'=>'form-control']) !!}
                            </div>
                            <div class="form-group col-md-2">
                                <label for="time_for_work">@lang('edit.timefor')</label><i class="fa fa-question-circle-o pull-right" aria-hidden="true"></i>

                                @php($name = isset($task_id) ? "sub-" . $task_id . "-time_for_work" : "time_for_work")
                                {!! Form::select($name, array('1' => '1 hour', '2' => '2 hours', '3' => '3 hours'), isset($job) ? $job->time_for_work : 1,['class'=>'form-control', 'id' => 'time_for_work'] )!!}
                            </div>
                            <div class="form-group col-md-3">
                                <label for="input-projects">@lang('edit.project')</label><i class="fa fa-question-circle-o pull-right" aria-hidden="true"></i>

                                @php($name = isset($task_id) ? "sub-" . $task_id . "-project_id" : "project_id")
                                <select name="{{ $name }}" id="input-projects" class="form-control">
                                    <option value="">@lang('edit.noproject')</option>

                                    @foreach($projects as $project)
                                        @if(((isset($job) && $project->id == $job->project_id)) ||
                                            $project->id == old('project_id') ||
                                            $project->id == request('project_id'))
                                            <option selected value="{{ $project->id }}">{{ $project->name }}</option>
                                        @else
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="input-tag">@lang('edit.cms')</label><i class="fa fa-question-circle-o pull-right" aria-hidden="true"></i>

                                @php($name = isset($task_id) ? "sub-" . $task_id . "-tag" : "tag")
                                <select name="{{ $name }}" id="input-tag" class="form-control">
                                    <option value="" selected>@lang('edit.nocms')</option>

                                    @foreach(config('tags.tags') as $tag)
                                        <option value="{{$tag['value']}}" {{isset($job) && isset($job->tag) && $job->tag->value == $tag['value'] ? 'selected' : ''}}>{{$tag['title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="job-row">
        @include('jobs.partials.upload', ['task_id' => (isset($task_id) ? $task_id : 0)])
    </div>
</div>

@if(isset($subtask))
    <script>
            $('.editor1').summernote({
                toolbar: [
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['picture','video']],
                ],
            });
    </script>

    <script>
        $("#task-{{$task_id}} .search-input").focus(function () {
            getUsers('{{$task_id}}', $(this).val());
        });

        $("#task-{{$task_id}} .search-input").keyup(function () {
            if(user_search !== $(this).val()) {
                user_search = $(this).val();

                getUsers('{{$task_id}}', $(this).val());
            }
        });

        $("#task-{{$task_id}} .search-input").on('blur', function (e) {
            setTimeout(function () {
                $("#task-{{$task_id}}").find('.search').find('.result').html('').hide();
            }, 2000);
        });
    </script>
@endif
