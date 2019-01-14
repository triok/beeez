<div id="panel{{ $structure->id }}"
     class="tab-pane fade col-sm-10 {{ ($structure->id == $structures->first()->id ? 'in active' : '') }}">

    <div class="col-sm-3 employee">
        <h2>@lang('structure.show_users')</h2>

        @if(auth()->user()->isOrganizationFullAccess($organization) || ($connection && $connection->can_add_user))
            <a href="{{ route('structure.edit', [$organization, $structure]) }}" class="btn btn-primary btn-xs">
                <i class="fa fa-plus"></i> @lang('structure.add_users')
            </a>
        @endif

        <ul class="list-unstyled">
            @foreach($structure->employees as $employee)
                <li>
                    {{ $employee->name }}

                    @if(!$employee->pivot->is_approved)
                        <i class="fa fa-warning" title="Не подтвержден"></i>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="col-sm-9">
        <h2>@lang('structure.projects')</h2>

        @if(auth()->user()->isOrganizationFullAccess($organization) || ($connection && $connection->can_add_project))
            <a href="{{ route('projects.create') . '?structure_id=' . $structure->id }}" class="btn btn-primary btn-xs">
                <i class="fa fa-plus"></i> @lang('projects.create')
            </a>
        @endif

        <ul class="list-unstyled">
            @foreach($structure->projects as $project)
                @php
                    $projectConnection = \App\Models\ProjectUsers::where('project_id', $project->id)
                        ->where('user_id', auth()->id())
                        ->first();
                @endphp

                @if($organization->user_id == auth()->id() ||
                    $project->user_id == auth()->id() ||
                    ($connection && $connection->can_see_all_projects) ||
                    $projectConnection)

                    <li>{{ $project->name }}</li>
                @endif
            @endforeach
        </ul>
    </div>
</div>