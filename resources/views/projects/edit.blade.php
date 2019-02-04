@extends('layouts.app')

@section('content')
    <div class="container edit-project" id="main">
        <h2>@lang('projects.edit-title')</h2>

        {!! Form::open(['url' => route('projects.update', $project), 'method'=>'patch']) !!}

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('name', $project->name, ['required'=>'required', 'class'=>'form-control', 'placeholder' => __('projects.create-name')]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::textarea('description', $project->description, ['class'=>'form-control', 'placeholder' => __('projects.create-desc')]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="team_id">@lang('projects.create-type')</label>
                    <select class="form-control" name="team_id">
                        <option value="">@lang('projects.create-personal')</option>

                        @if($project->project_type == 'organization')
                            <option selected value="organization">Проект организации</option>
                        @else
                            <option value="organization">Проект организации</option>
                        @endif

                        @foreach($teams as $team)
                            @if($team->id == $project->team_id)
                                <option selected value="{{ $team->id }}">{{ $team->name }}</option>
                            @else
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="team_id">Отдел</label>
                    <select class="form-control" name="structure_id">
                        <option value="">Нет</option>
                        @foreach($organizations as $organization)
                            @foreach($organization->structures as $structure)
                                @if($structure->id == $project->structure_id)
                                    <option selected
                                            value="{{ $structure->id }}">{{ $organization->name . ' -> ' . $structure->name }}</option>
                                @else
                                    <option value="{{ $structure->id }}">{{ $organization->name . ' -> ' . $structure->name }}</option>
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>@lang('projects.deadline')</label>
                    <input name="deadline_at" class="form-control timepicker" type="text"
                           value="{{ $project->deadline_at ? $project->deadline_at->format('d.m.Y H:i') : '' }}"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @include('projects.partials.form_users')
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @include('projects.partials.form_followers')
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="icon">@lang('projects.create-icon')</label>
                    <select class="form-control" style="font-family: FontAwesome;" name="icon">
                        <option>@lang('projects.create-noicon')</option>
                        @foreach($icons as $icon_name=>$icon_code)
                            @if($icon_name == $project->icon)
                                <option selected value="{{ $icon_name }}">&#x{{ $icon_code }}</option>
                            @else
                                <option value="{{ $icon_name }}">&#x{{ $icon_code }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="submit" class="btn btn-primary" value="submit">@lang('projects.edit-btn')</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="/css/datepicker.min.css"/>
@endpush

@push('scripts')
    <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="/js/datepicker.min.js"></script>
    <script type="application/javascript">
        $('.timepicker').datepicker({
            timepicker: true,
            startDate: new Date(),
            minHours: 9,
            maxHours: 24,
            minDate: new Date(),
            onSelect: function (fd, d, picker) {
                if (!d) return;

                picker.update({
                    minHours: 0,
                    maxHours: 24
                })
            }
        });
    </script>
@endpush