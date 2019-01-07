@extends('layouts.app')

@section('content')

<div class="container-fluid" id="main">

        @foreach ($teams as $team) 

        <div class="col-xs-6">
            <div class="base-wrapper team-projects">
                <h2>Команда: {{ $team->name }}</h2>
                <div class="pull-right"><a href="{{ ($team->slug) }}">Информация</a></div>
                <div><p><span>Администратор:</span> <span><a href="{{ route('peoples.show', $AdminName->id) }}">{{ $AdminName->username }}</a></span></p></div>
                <hr>
                <h3>Проекты:</h3>
                <div class="pull-right">
                    <a href="{{ route('projects.create') }}?team_id={{ $team->id }}"
                                   class="btn btn-primary btn-block">
                        <i class="fa fa-sitemap"></i> @lang('projects.create')
                    </a>
                </div>
                @include('teams.partials.projects')
            </div>           
        </div>
  

        @endforeach

</div>

<!--     <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-md-12 teams">
                <h2>@lang('teams.title')</h2>
                <div class="row">
                    <div class="col-md-2 search">
                        <input type="text" class="form-control" id="team_search" placeholder="@lang('teams.search')"><i class="fa fa-search" aria-hidden="true"></i>
                        <ul class="result"></ul>
                    </div>
                    <div class="col-md-2 search">
                        <select id="team-type-filter" class="form-control" style="border: none; border-bottom: 1px solid #ccd0d2;">
                            <option value="">тип команды ...</option>

                            @foreach($teamTypes as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 search-button">
                        <a href="{{ route('teams.create') }}" class="btn btn-primary btn-md pull-right">
                            <i class="fa fa-plus-circle"></i> @lang('teams.create_team')
                        </a>
                    </div>
                </div>
                <table class="table table-striped table-responsive table-full-width table-search" id="teams-table">
                    <thead>
                    <tr>
                        <th>@lang('teams.team')</th>
                        <th>@lang('teams.owner')</th>
                        <th>@lang('teams.show_team_type')</th>
                        <th>@lang('teams.show_date')</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div> -->
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush

@push('scripts')
<!--     <script>
        var auth_id = "{{ auth()->id() }}";

        $(document).ready(function () {
            var table = $('#teams-table').DataTable({
                bFilter: false,
                bInfo: false,
                "lengthChange": false,

                "pageLength": 20,

                "fnDrawCallback": function(oSettings) {
                    if ($('#teams-table tr').length < 21) {
                        $('.dataTables_paginate').hide();
                    }
                },

                "ajax": {
                    "url": "/api/teams/search?user_id=" + auth_id,
                    "dataSrc": "data"
                },

                "columns": [
                    {
                        "data": "name",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                var res = '<a href="' + row.route + '">' + row.name + '</a>';

                                if (parseInt(auth_id) == parseInt(row.owner.id)) {
                                    res += ' <i class="fa fa-star"></i>'
                                }

                                return res;
                            }

                            return data;
                        }
                    },
                    {
                        "data": "owner.name",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<a href="' + row.owner.route + '">' + row.owner.name + '</a>';
                            }

                            return data;
                        }
                    },
                    {"data": "type"},
                    {
                        "data": "created_at",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<span class="date-short">' + moment(data, "YYYY-MM-DD mm:ss").format("ll") + '</span>';
                            }

                            return data;
                        }
                    },
                ]
            });

            $('#team-type-filter').on( 'change', function () {
                table.ajax.url("api/v1/teams/search?user_id=" + auth_id + "&type=" + $(this).val()).load();
            });
        });
    </script> -->
@endpush