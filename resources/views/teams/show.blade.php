@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="row">
        <div class="col-md-12 teams-show">
            <div style="margin-bottom: 5px;">
                <a href="{{ route('teams.index') }}">
                    <span><i class="fa fa-arrow-left"></i> @lang('teams.back_to_list')</span>
                </a>
                @if($team->user_id == auth()->user()->id)
                <a href="{{ route('teams.edit', $team) }}" class="btn btn-default btn-xs pull-right">
                    <i class="fa fa-pencil"></i> @lang('teams.edit')
                </a>
                @endif                
                <hr>

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
                            <table class="table table-responsive table-search">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <td><b>@lang('teams.show_name')</b></td>
                                        <td><span class="team-info">{{ $team->name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><b>@lang('teams.show_date')</b></td>
                                        <td><span class="team-info date-short">{{ $team->created_at }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><b>@lang('teams.show_team_type')</b></td>
                                        <td><span class="team-info">{{ $team->type->name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><b>@lang('teams.show_description')</b></td>
                                        <td><p>{!! $team->description !!}</p></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="2">
                                         <table class="table table-responsive">
                                            <thead>
                                            <tr>
                                                <td>@lang('teams.show_user_name')</td>
                                                <td>@lang('teams.show_user_position')</td>
                                                <td>@lang('teams.show_user_date')</td>
                                                <td class="text-right">Статус</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($connections as $connection)
                                            <tr>
                                                <td><a href="{{ route('peoples.show', $connection->user) }}">{{ $connection->user->name }}</a></td>
                                                <td>{{ $connection->position }}</td>
                                                <td class="date-short">{{ $connection->created_at }}</td>
                                                <td class="text-right">{{ ($connection->is_approved ? 'Подтвержден' : 'В ожидании') }}</td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            {!! Form::open(['url' => route('threads.store') . '?team_id=' . $team->id, 'method'=>'post']) !!}
                            @if($team->user_id != auth()->id())
                                <input type="hidden" name="connections[{{ $team->user_id }}]" value="user">
                            @endif

                            @foreach($connections as $connection)
                                @if($connection->user->id != auth()->id())
                                    <input type="hidden" name="connections[{{ $connection->user->id }}]" value="user">
                                @endif
                            @endforeach

                            <button type="submit" onclick="" class="btn btn-xs btn-success">
                                Чат с командой
                            </button>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection