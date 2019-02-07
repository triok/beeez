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
    <td>{{ $project->teamJobs()->count() }}/0</td>
    <td class="text-right" style="width: 100px;">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

        @if(isset($followed) && $followed)
                    {!! Form::open(['url' => route('projects.unfollow', $project)]) !!}
                    <button type="submit" onclick="" class="btn btn-xs btn-primary btn-round" title="Отписаться">
                        <i class="fa fa-close fa-fw"></i>
                    </button>
                    {!! Form::close() !!}
        @else

        @if($project->isOwn())
            {!! Form::open(['url' => route('projects.edit', $project)]) !!}
                <button type="submit" onclick="" class="btn btn-xs btn-primary btn-round" title="@lang('projects.edit')">
                    <i class="fa fa-pencil fa-fw"></i>
                </button>
            {!! Form::close() !!}  
        @endif

        @if(!$project->isArchived() && !$project->isFavorited())
            {!! Form::open(['url' => route('projects.favorite', $project) . '?team_id=' . $project->team_id, 'method'=>'post']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-default btn-round"
                    title="@lang('projects.favorite_add')">
                <i class="fa fa-star-o fa-fw"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if(!$project->isArchived() && $project->isFavorited())
            {!! Form::open(['url' => route('projects.unfavorite', $project) . '?team_id=' . $project->team_id, 'method'=>'post']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-default btn-round"
                    title="@lang('projects.favorite_del')">
                <i class="fa fa-star fa-fw" style="color: orange;"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if(!$project->isArchived())
            {!! Form::open(['url' => route('projects.done', $project) . '?team_id=' . $project->team_id, 'method'=>'post', 'class' => 'form-delete']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-success btn-round" 
                    title="@lang('projects.complete')">
                <i class="fa fa-check fa-fw"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if($project->isArchived())
            {!! Form::open(['url' => route('projects.restore', $project) . '?team_id=' . $project->team_id, 'method'=>'post', 'class' => 'form-delete']) !!}
            <button type="submit" onclick="" class="btn btn-xs btn-warning btn-round" title="@lang('projects.restore')">
                <i class="fa fa-refresh fa-fw"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @if($project->isOwn())
            {!! Form::open(['url' => route('projects.destroy', $project) . '?team_id=' . $project->team_id, 'method'=>'delete', 'class' => 'form-delete']) !!}
            <button type="submit" onclick=""
                    class="btn btn-xs btn-danger btn-round"
                    title="@lang('projects.delete')">
                <i class="fa fa-trash fa-fw"></i>
            </button>
            {!! Form::close() !!}
        @endif

        @endif
            </div>
        </div>
    </td>
</tr>