@extends('layouts.app')
@section('content')
    <h2>Edit project</h2>

    {!! Form::open(['url' => route('projects.update', $project), 'method'=>'patch']) !!}

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::text('name', $project->name, ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Project title']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::textarea('description', $project->description, ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Project description']) !!}
            </div>
        </div>
    </div>

    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="submit" class="btn btn-success" value="submit">Save</button>
        </div>
    </div>

    {!! Form::close() !!}
@endsection
