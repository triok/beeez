<tr class="sort-row" id="{{ $project->id }}">
    <td>
        @if($project->icon)
            <i class="fa {{ $project->icon }}"></i>
        @endif
    </td>
    <td>
        <a href="{{ route('projects.show', $project) . '?team_id=' . $project->team_id }}">{{ $project->name }}</a>
    </td>
    <td>{{ $project->description }}</td>
    <td>{{ $project->jobs()->count() }}/0</td>
    <td class="text-right">
        @if($project->user_id == auth()->id())
            <a href="{{ route('projects.edit', $project) }}">
                <i class="fa fa-pencil btn btn-xs btn-default"></i>
            </a>
        @endif

        @if(!$project->is_archived && !$project->is_favorite)
            {!! Form::open(['url' => route('projects.favorite', $project) . '?team_id=' . $project->team_id, 'method'=>'post']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-default"
                    title="Избранный">
                <i class="fa fa-star-o"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if(!$project->is_archived && $project->is_favorite)
            {!! Form::open(['url' => route('projects.unfavorite', $project) . '?team_id=' . $project->team_id, 'method'=>'post']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-default"
                    title="Удалить с избранных">
                <i class="fa fa-star" style="color: orange;"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if(!$project->is_archived)
            {!! Form::open(['url' => route('projects.done', $project) . '?team_id=' . $project->team_id, 'method'=>'post', 'class' => 'form-delete']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-success" title="Выполнено">
                <i class="fa fa-check"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if($project->is_archived)
            {!! Form::open(['url' => route('projects.restore', $project) . '?team_id=' . $project->team_id, 'method'=>'post', 'class' => 'form-delete']) !!}
            <button type="submit" onclick="" class="btn btn-xs btn-warning" title="Возобновить">
                <i class="fa fa-refresh"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if($project->user_id == auth()->id())
            {!! Form::open(['url' => route('projects.destroy', $project) . '?team_id=' . $project->team_id, 'method'=>'delete', 'class' => 'form-delete']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-danger">
                <i class="fa fa-trash"></i>
            </button>
            {!! Form::close() !!}
        @endif
    </td>
</tr>