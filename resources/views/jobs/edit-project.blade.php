@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {!! Form::model($job, ['url' => route('jobs.updateProject', $job->id), 'method'=>'post']) !!}

        <div class="row">
            <div class="form-group col-md-6">
                <label for="input-projects">Переместить в проект</label>

                <select name="team_project_id" id="input-projects" class="form-control">
                    @foreach($projects as $project)
                        @if($project->id != $job->project_id && !$project->is_temporary)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <button type="submit" class="btn btn-primary">
                    Сохранить
                </button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection