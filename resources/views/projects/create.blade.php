@extends('layouts.app')
@section('content')
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

    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="submit" class="btn btn-success" value="submit">Create</button>
        </div>
    </div>

    {!! Form::close() !!}
@endsection
