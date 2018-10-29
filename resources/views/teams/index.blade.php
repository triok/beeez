@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-md-12 teams">
                <h2>@lang('teams.title')</h2>

                <div class="row">
                    <div class="col-md-2 search">
                        <input type="text" class="form-control" id="team_search" placeholder="@lang('teams.search')">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <ul class="result"></ul>
                    </div>

                    <div class="col-md-2 search">
                        <select id="team-type-filter" class="form-control"
                                style="border: none; border-bottom: 1px solid #ccd0d2;">

                            <option value="">Тип команды ...</option>

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

                @include('teams.partials.table', ['action' => '/api/teams/search?all=true'])
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush