@if(isset($teamProjects[$team->id]) && $teamProjects[$team->id]->count())
    <ul class="nav nav-tabs">
        <li role="presentation" class="active">
            <a data-toggle="tab" href="#team-{{ $team->id }}-current">
                @lang('projects.current')
            </a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#team-{{ $team->id }}-completed">
                @lang('projects.completed')
            </a>
        </li>
        <li role="presentation">
            <a data-toggle="tab" href="#team-{{ $team->id }}-favorite">
                @lang('projects.favorite')
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="team-{{ $team->id }}-current" class="tab-pane fade in active">
            @php
                $projectsNotArchived = $teamProjects[$team->id]->filter(function ($item) {
                    return !$item->isArchived();
                });
            @endphp

            @if($projectsNotArchived->count())
                <table class="table table-responsive table-projects">
                    <thead>
                    <tr>
                        <td></td>
                        <td>@lang('projects.name')</td>
                        <td>@lang('projects.desc')</td>
                        <td>@lang('projects.count')</td>
                        <td></td>
                    </tr>
                    </thead>

                    <tbody class="sortable-rows">
                    @foreach($projectsNotArchived as $project)
                        @include('teams.partials.project')
                    @endforeach
                    </tbody>
                </table>
            @else
                @lang('projects.teamnoprojects')
            @endif
        </div>

        <div id="team-{{ $team->id }}-favorite" class="tab-pane fade">
            @php
                $projectsFavorite = $teamProjects[$team->id]->filter(function ($item) {
                    return (!$item->isArchived() && $item->isFavorited());
                });
            @endphp

            @if($projectsFavorite->count())
                <table class="table table-responsive table-projects">
                    <thead>
                    <tr>
                        <td></td>
                        <td>@lang('projects.name')</td>
                        <td>@lang('projects.desc')</td>
                        <td>@lang('projects.count')</td>
                        <td></td>
                    </tr>
                    </thead>

                    <tbody class="sortable-rows">
                    @foreach($projectsFavorite as $project)
                        @include('teams.partials.project')
                    @endforeach
                    </tbody>
                </table>
            @else
                @lang('projects.teamnoprojects')
            @endif
        </div>

        <div id="team-{{ $team->id }}-completed" class="tab-pane fade">
            @php
                $projectsArchived = $teamProjects[$team->id]->filter(function ($item) {
                    return $item->isArchived();
                });
            @endphp

            @if($projectsArchived->count())
                <table class="table table-responsive table-projects">
                    <thead>
                    <tr>
                        <td></td>
                        <td>@lang('projects.name')</td>
                        <td>@lang('projects.desc')</td>
                        <td>@lang('projects.count')</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody class="sortable-rows">
                    @foreach($projectsArchived as $project)
                        @include('teams.partials.project')
                    @endforeach
                    </tbody>
                </table>
            @else
                @lang('projects.teamnoprojects')
            @endif
        </div>
    </div>
@else
    @lang('projects.teamnoprojects')
@endif

@push('scripts')
    <script src="/js/custom.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $(".sortable-rows").sortable({
                placeholder: "ui-state-highlight",
                update: function (event, ui) {
                    updateDisplayOrder();
                }
            });
        });

        // function to save display sort order
        function updateDisplayOrder() {
            var selectedLanguage = [];
            $('.sortable-rows .sort-row').each(function () {
                selectedLanguage.push($(this).attr("id"));
            });
            var dataString = 'sort_order=' + selectedLanguage;
            $.ajax({
                type: "POST",
                url: "/order-projects",
                data: dataString,
                cache: false,
                success: function (data) {
                }
            });
        }
    </script>
@endpush