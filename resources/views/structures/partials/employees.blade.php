<div id="panel{{ $structure->id }}"
     class="tab-pane fade col-sm-10 {{ ($structure->id == $structures->first()->id ? 'in active' : '') }}">

    <div class="col-sm-3 employee">
        <h2>@lang('structure.show_users')</h2>

        @if($organization->user_id == auth()->id() || ($connection && $connection->can_add_user))
            <a href="{{ route('structure.edit', [$organization, $structure]) }}" class="btn btn-primary btn-xs">
                <i class="fa fa-plus"></i> @lang('structure.add_users')
            </a>
        @endif

        <ul class="list-unstyled">
            @foreach($structure->employees as $employee)
                <li>{{ $employee->name }}</li>
            @endforeach
        </ul>
    </div>

    <div class="col-sm-9">
        <h2>@lang('structure.projects')</h2>

        @if($organization->user_id == auth()->id() || ($connection && $connection->can_add_project))
            <a href="{{ route('projects.create') . '?structure_id=' . $structure->id }}" class="btn btn-primary btn-xs">
                <i class="fa fa-plus"></i> @lang('projects.create')
            </a>
        @endif

        <ul class="list-unstyled">
            @foreach($structure->projects as $project)
                @if($organization->user_id == auth()->id() ||
                    $project->user_id == auth()->id() ||
                    ($connection && $connection->can_see_all_projects))

                    <li>{{ $project->name }}</li>
                @endif
            @endforeach
        </ul>
    </div>
</div>