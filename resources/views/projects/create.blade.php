@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <h2>@lang('projects.title-create')</h2>

    {!! Form::open(['url' => route('projects.store')]) !!}

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::text('name', old('name'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Project title']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::textarea('description', old('description'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Project description']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <select class="form-control" name="team_id">
                    <option>персональный</option>
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
                <select class="form-control" style="font-family: FontAwesome;" name="icon">
                    <option>без иконки</option>
                    @foreach($icons as $icon_name=>$icon_code)
                        <option value="{{ $icon_name }}">&#x{{ $icon_code }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="submit" class="btn btn-success" value="submit">Create</button>
        </div>
    </div>

    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
    <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
@endpush