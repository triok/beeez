@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="teams-projects">
        <div class="row">
            <div class="col-md-12">
                <h2>@lang('teams.project-title')</h2>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="nav nav-pills">
                            @foreach($teams as $team)
                                <li role="presentation" class="{{ ($team->id == $teamSelected ? 'active' : '') }}">
                                    <a data-toggle="tab" href="#team-{{ $team->id }}">
                                        {{ $team->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="tab-content">
                    @foreach($teams as $team)
                        <div id="team-{{ $team->id }}"
                             class="tab-pane fade {{ ($team->id == $teams->first()->id ? 'in active' : '') }}">

                            <div class="pull-right">
                                <a href="{{ route('projects.create') }}?team_id={{ $team->id }}"
                                   class="btn btn-primary btn-block">

                                    <i class="fa fa-sitemap"></i> @lang('projects.create')
                                </a>
                            </div>

                            @include('teams.partials.projects')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
