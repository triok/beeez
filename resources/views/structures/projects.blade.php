@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="teams-projects">
        <div class="row">
            <div class="col-md-12">
                <h2>Проекты в командах</h2>

                <ul class="nav nav-pills">
                    @foreach($teams as $team)
                        <li role="presentation" class="{{ ($team->id == $teamSelected ? 'active' : '') }}">
                            <a data-toggle="tab" href="#team-{{ $team->id }}">
                                {{ $team->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
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

@push('styles')
    <style>
        .tab-content {
            margin-top: 20px;
        }

        .table-responsive tr td:first-child {
            width: 20px;
        }

        .table-responsive tr td:last-child {
            min-width: 200px;
        }

        .table-responsive form {
            display: inline-block;
        }
    </style>
@endpush