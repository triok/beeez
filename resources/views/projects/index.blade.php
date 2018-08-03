@extends('layouts.app')

@section('content')
    <h2>@lang('projects.title')</h2>

    <div class="col-sm-2 pull-right">
        <a href="{{ route('projects.create') }}" class="btn btn-success btn-block">
            <i class="fa fa-sitemap"></i> @lang('projects.create')
        </a>
    </div>

    <table class="table table-responsive">
        <thead>
        <tr>
            <td>@lang('projects.name')</td>
            <td>@lang('projects.desc')</td>
            <td style="min-width: 200px;">@lang('projects.count')</td>
            <td></td>
        </tr>
        </thead>

        <tbody>
        @if($projects->count())
            @foreach($projects as $project)
                <tr>
                    <td><a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a></td>
                    <td>{{ $project->description }}</td>
                    <td>{{ $project->jobs()->count() }}/0</td>
                    <td class="text-right">
                        <a href="{{ route('projects.edit', $project) }}">
                            <i class="fa fa-pencil btn btn-xs btn-default"></i>
                        </a>

                        {!! Form::open(['url' => route('projects.destroy', $project), 'method'=>'delete', 'class' => 'form-delete']) !!}
                        <button type="submit" onclick="" class="btn btn-xs btn-danger">
                            <i class="fa fa-trash"></i>
                        </button>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3">
                    @lang('projects.noprojects')
                </td>
            </tr>
        @endif
        </tbody>
    </table>
@endsection

@push('scripts')
    <script src="/js/custom.js"></script>
@endpush
