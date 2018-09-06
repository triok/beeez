@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="row">
        <div class="col-md-12">
            <hr>

            <div style="margin-bottom: 5px;">
                <a href="{{ route('teams.index') }}">
                    <span><i class="fa fa-arrow-left"></i> @lang('teams.back_to_list')</span>
                </a>

                @if($team->user_id == auth()->user()->id)
                <a href="{{ route('teams.edit', $team) }}" class="btn btn-default btn-xs pull-right">
                    <i class="fa fa-pencil"></i> @lang('teams.edit')
                </a>
                @endif
            </div>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{ $team->logo() }}"
                                 class="img-thumbnail"
                                 alt="{{ $team->name }}"
                                 title=" {{$team->name }}"
                                 style="width: 100px; height: 100px;">
                        </div>

                        <div class="col-md-8">
                            <p>
                                <b>@lang('teams.show_name')</b> <span>{{ $team->name }}</span>
                            </p>

                            <p>
                                <b>@lang('teams.show_date')</b>
                                <span>{{ \Carbon\Carbon::parse($team->created_at)->format('d M, Y') }}</span>
                            </p>

                            <p>
                                <b>@lang('teams.show_team_type')</b>
                                <span>{{ $team->type->name }}</span>
                            </p>

                            <b>@lang('teams.show_description')</b>
                            <p>{!! $team->description !!}</p>

                            <hr>

                            <table class="table table-responsive">
                                <thead>
                                <tr>
                                    <td>@lang('teams.show_user_name')</td>
                                    <td>@lang('teams.show_user_position')</td>
                                    <td class="text-right">@lang('teams.show_user_date')</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($connections as $connection)
                                <tr>
                                    <td><a href="{{ route('peoples.show', $connection->user) }}">{{ $connection->user->name }}</a></td>
                                    <td>{{ $connection->position }}</td>
                                    <td class="text-right">{{ \Carbon\Carbon::parse($connection->created_at)->format('d M, Y') }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection