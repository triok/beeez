@extends('layouts.app')
@section('content')
    <div class="container create-project" id="main">
        <h2>@lang('projects.create-title')</h2>

        {!! Form::open(['url' => route('projects.store')]) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('name', old('name'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => __('projects.create-name')]) !!}
                    <small>Обязательное поле</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'rows' => '3', 'placeholder' => __('projects.create-desc')]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="team_id">@lang('projects.create-type')</label>
                    <select class="form-control" name="team_id">
                        <option value="">@lang('projects.create-personal')</option>

                        @if(!$team_id && $structure_id)
                            <option selected value="organization">Проект организации</option>
                        @else
                            <!-- <option value="organization">Проект организации</option> -->
                        @endif

                        @foreach($teams as $team)
                            @if($team->id == $team_id)
                                <option selected
                                        value="{{ $team->id }}">@lang('projects.create-inteam') {{ $team->name }}</option>
                            @else
                                <option value="{{ $team->id }}">@lang('projects.create-inteam') {{ $team->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <small>Обязательное поле</small>
                </div>
            </div>
        </div>
        @if ($structures->count())
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="team_id">Отдел</label>
                        <select class="form-control" name="structure_id">
                            <option value="">Нет</option>

                            @foreach($structures as $structure)
                                @if($structure->id == $structure_id)
                                    <option selected value="{{ $structure->id }}">
                                        {{ $structure->organization->name . ' -> ' . $structure->name }}
                                    </option>
                                @else
                                    <option value="{{ $structure->id }}">
                                        {{ $structure->organization->name . ' -> ' . $structure->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>@lang('projects.deadline')</label>
                    <input name="deadline_at" class="form-control timepicker" type="text"
                           value="{{ old('deadline_at') }}" autocomplete="off"/>
                </div>
            </div>
        </div>

        @if ($structures->count() || $teams->count())
        <div class="row">
            <div class="col-md-6">
                @include('projects.partials.form_users')
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                @include('projects.partials.form_followers')
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="icon">@lang('projects.create-icon')</label>
                    <select class="form-control icons" style="font-family: FontAwesome;" name="icon">
                        <option>@lang('projects.create-noicon')</option>
                        @foreach($icons as $icon_name=>$icon_code)
                            <option value="{{ $icon_name }}">&#x{{ $icon_code }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>


        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="submit" class="btn btn-primary" value="submit">@lang('projects.create-btn')</button>
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