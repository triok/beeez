@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-xs-3">
                <div class="base-wrapper">
                    <h2>@lang('teams.title')</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate, explicabo, reprehenderit. Eveniet a aliquam unde aliquid pariatur perferendis maiores dolores voluptatem, rerum dicta sint vitae maxime eos totam cum quidem.</p>
                    <div class="search-button">
                        <a href="{{ route('teams.create') }}" class="btn btn-primary btn-md">
                            <i class="fa fa-plus-circle"></i> @lang('teams.create_team')
                        </a>
                    </div>                    
                </div>
                <div class="base-wrapper">
                    <h2>@lang('teams.navigation')</h2>
                    <div class="search">
                        <input type="text" class="form-control" id="team_search" placeholder="@lang('teams.search')">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <ul class="result"></ul>
                    </div>
                    <ul class="list-unstyled" id="team-type-filter">
                        @foreach($teamTypes as $key => $value)
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           value="{{ $key }}"
                                           id="input-team-type-{{ $key }}">

                                    <label class="form-check-label label-text" for="input-team-type-{{ $key }}">
                                        {{ $value }}
                                    </label>
                                </div>                                
                            </li>
                        @endforeach
                    </ul>              
            </div>
        </div>
            <div class="col-xs-9 teams">
                <div class="base-wrapper">
                @include('teams.partials.table', ['action' => '/api/teams/search?all=true'])
                </div>
            </div>
        </div>

    </div>

@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush


