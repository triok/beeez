@php
    $connection = \App\Models\StructureUsers::where('structure_id', $project->structure_id)
        ->where('user_id', auth()->id())
        ->where('is_approved', true)
        ->first();

    $projectConnection = \App\Models\ProjectUsers::where('project_id', $project->id)
        ->where('user_id', auth()->id())
        ->first();
@endphp

@if(auth()->user()->isOrganizationFullAccess($organization)
    || auth()->id() == $project->user_id
    || ($connection && $projectConnection)
    || ($connection && $connection->can_see_all_projects))
<tr class="sort-row" id="{{ $project->id }}">
    <td>
        @if($project->icon)
            <i class="fa {{ $project->icon }}"></i>
        @endif
    </td>
    <td>
        <a href="{{ route('projects.show', $project) . '?structure_id=' . $project->structure_id }}">{{ $project->name }}</a>
    </td>
    <td>{{ $project->description }}</td>
    <td>{{ $project->jobs()->count() }}/0</td>
    <td class="text-right">
        @if($project->isOwn() || auth()->user()->isOrganizationFullAccess($organization))
            {!! Form::open(['url' => route('projects.edit', $project)]) !!}
                <button type="submit" onclick="" class="btn btn-xs btn-primary" title="@lang('projects.edit')">
                    <i class="fa fa-pencil"></i>
                </button>
            {!! Form::close() !!}  
        @endif

        @if(!$project->isArchived() && !$project->isFavorited())
            {!! Form::open(['url' => route('projects.favorite', $project) . '?structure_id=' . $project->structure_id, 'method'=>'post']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-default"
                    title="@lang('projects.favorite_add')">
                <i class="fa fa-star-o"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if(!$project->isArchived() && $project->isFavorited())
            {!! Form::open(['url' => route('projects.unfavorite', $project) . '?structure_id=' . $project->structure_id, 'method'=>'post']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-default"
                    title="@lang('projects.favorite_del')">
                <i class="fa fa-star" style="color: orange;"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if(!$project->isArchived())
            {!! Form::open(['url' => route('projects.done', $project) . '?structure_id=' . $project->structure_id, 'method'=>'post', 'class' => 'form-delete']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-success" 
                    title="@lang('projects.complete')">
                <i class="fa fa-check"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if($project->isArchived())
            {!! Form::open(['url' => route('projects.restore', $project) . '?structure_id=' . $project->structure_id, 'method'=>'post', 'class' => 'form-delete']) !!}
            <button type="submit" onclick="" class="btn btn-xs btn-warning" title="@lang('projects.restore')">
                <i class="fa fa-refresh"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if($project->isOwn() || auth()->user()->isOrganizationFullAccess($organization))
            {!! Form::open(['url' => route('projects.destroy', $project) . '?structure_id=' . $project->structure_id, 'method'=>'delete', 'class' => 'form-delete']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-danger"
                    title="@lang('projects.delete')">
                <i class="fa fa-trash"></i>
            </button>
            {!! Form::close() !!}
        @endif
    </td>
</tr>
@endif