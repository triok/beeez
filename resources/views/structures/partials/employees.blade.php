<div id="panel{{ $structure->id }}"
     class="tab-pane fade col-sm-10 {{ ($structure->id == $structures->first()->id ? 'in active' : '') }}">

    <div class="col-sm-3 employee">
        <h2>@lang('structure.show_users')</h2>

        <a href="{{ route('structure.edit', [$organization, $structure]) }}" class="btn btn-primary btn-xs">
            <i class="fa fa-plus"></i> @lang('structure.add_users')
        </a>

        <ul class="list-unstyled">
            @foreach($structure->employees as $employee)
                <li>{{ $employee->name }}</li>
            @endforeach
        </ul>
    </div>

    <div class="col-sm-9">
        <h2>@lang('structure.projects')</h2>

        <a href="{{ route('projects.create') . '?structure_id=' . $structure->id }}" class="btn btn-primary btn-xs">
            <i class="fa fa-plus"></i> @lang('projects.create')
        </a>

        <ul class="list-unstyled">
            @foreach($structure->projects as $project)
                <li>{{ $project->name }}</li>
            @endforeach
        </ul>
    </div>
</div>