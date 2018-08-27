@extends('layouts.app')

@section('content')
<div class="container" id="main">
    <h2>{{ $project->name }}</h2>

    @permission('create-jobs')
    <a href="{{ route('jobs.create') }}" class="btn btn-success" style="margin-top: 10px;">Post new job</a>
    @endpermission

    <div class="col-xs-12">
        <table class="table table-sm table-hover">
            <thead>
            <tr>
                <td>@lang('projects.job-name')</td>
                <td>@lang('projects.deadline')</td>
                <td>@lang('projects.executor')</td>
                <td>@lang('projects.price')</td>
                <td>@lang('projects.published')</td>
            </tr>
            </thead>

            <tbody>
            @foreach($project->jobs as $job)
            <tr>
                <td><a href="{{ route('jobs.show', $job) }}">{{ $job->name }}</a></td>
                <td>{{ \Carbon\Carbon::parse($job->end_date)->format('d M, Y H:i') }}</td>
                <td>
                    @if(count($job->applications) > 0)
                        {{ $job->applications()->first()->user->name }}
                    @else
                        нет
                    @endif
                </td>
                <td>${{ $job->price }}</td>
                <td>Опубликовано</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="pull-right">
        <p>
            <span>@lang('projects.jobs-total')</span> {{ $project->jobs()->count() }}
            <span>@lang('projects.summ-total')</span> ${{ $project->jobs()->sum('price') }}
        </p>
        <p><span>@lang('projects.jobs-complete')</span> 0</p>
    </div>
</div>    
@endsection