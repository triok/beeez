@extends('layouts.app')
@section('content')
<div class="container create-project" id="main">
    <h2>@lang('projects.create-title')</h2>

    {!! Form::open(['url' => route('projects.store')]) !!}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::text('name', old('name'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => __('projects.create-name')]) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'rows' => '5', 'placeholder' => __('projects.create-desc')]) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="team_id">@lang('projects.create-type')</label>
                <select class="form-control" name="team_id">
                    <option value="">@lang('projects.create-personal')</option>
                    @foreach($teams as $team)
                        @if($team->id == $team_id)
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
                            @if($structure->id == $structure_id)
                                <option selected value="{{ $structure->id }}">{{ $organization->name . ' -> ' . $structure->name }}</option>
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

@push('scripts')
    <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
@endpush