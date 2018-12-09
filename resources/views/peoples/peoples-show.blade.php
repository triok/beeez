@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="row">
        <div class="col-md-12 peoples-show">
            <a href="{{route('peoples.index')}}"><span><i class="fa fa-arrow-left"></i> @lang('peoples.back')</span></a>
            <hr>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{$user->getStorageDir() . $user->avatar}}" class="img-thumbnail" alt="{{$user->name}}" title="{{$user->name}}" style="width: 100px; height: 100px;">
                        </div>
                        <div class="col-md-8">
                            <table class="table table-responsive table-search">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 30%"><b>@lang('peoples.login')</b></td>
                                        <td><span class="people-info">{{$user->username}}</span> (<span class="text-success">{{$user->rating_positive}}</span>/<span class="text-danger">{{$user->rating_negative}}</span>)</td>
                                    </tr>
                                    <tr>
                                        <td><b>@lang('peoples.name')</b></td>
                                        <td><span class="people-info">{{$user->name}}</span></td>
                                    </tr>
                                    <tr>
                                        <td><b>@lang('peoples.member')</b></td>
                                        <td><span class="people-info date-short">{{ $user->created_at }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><b>@lang('peoples.social')</b></td>
                                        <td><ul class="list-group">
                                            @php($socialCount = 0)
                                            @if(isset($user->socialLinks))
                                                @foreach($user->socialLinks as $social)
                                                    @if(isset($social['obj']) && $social['obj']->pivot->status == config('tags.statuses.confirmed.value'))
                                                        {{$social['title']}}<li class="list-group-item list-group-item-success">{{$social['obj']->pivot->link}}<span class="badge">confirmed</span></li>
                                                        @php($socialCount++)
                                                    @endif
                                                @endforeach
                                            @endif

                                            @if(!$socialCount)
                                                <span class="people-info">@lang('peoples.nosocial')</span> 
                                            @endif   
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>@lang('peoples.bio')</b></td>
                                        <td>
                                            @if(isset($user->bio))
                                            <p>{{$user->bio}}</p>
                                            @else
                                            <span class="people-info">@lang('peoples.nobio')</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @if($user->show_working_hours)
                                        @php($working_hours = $user->working_hours ? json_decode($user->working_hours, true) : [])
                                        <tr>
                                            <td><b>Время работы</b></td>
                                            <td>
                                                <table class="table table-responsive">
                                                    <tbody>
                                                    @foreach(config('enums.days') as $day=>$num)
                                                        <tr>
                                                            <td style="width: 20%">
                                                                @lang('account.days.' . $day)
                                                            </td>
                                                            <td style="width: 30%">
                                                                @if(isset($working_hours[$num]) && $working_hours[$num]['start'])
                                                                    {{ $working_hours[$num]['start'] }}
                                                                @else
                                                                    <b class="text-warning">выходной</b>
                                                                @endif
                                                            </td>
                                                            <td style="width: 35%">
                                                                @if(isset($working_hours[$num]) && $working_hours[$num]['start'] && $working_hours[$num]['end'])
                                                                    {{ $working_hours[$num]['end'] }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <i class="fa fa-calendar-times-o {{ (isset($working_hours[$num]) && $working_hours[$num]['start'] ? 'hide' : '') }}"></i>
                                                                <i class="fa fa-calendar-check-o {{ (isset($working_hours[$num]) && $working_hours[$num]['start'] ? '' : 'hide') }}"></i>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td><b>Страна</b></td>
                                        <td>{{ $user->country }}</td>
                                    </tr>

                                    <tr>
                                        <td><b>Город</b></td>
                                        <td>{{ $user->city }}</td>
                                    </tr>

                                    <tr>
                                        <td><b>@lang('peoples.skills')</b></td>
                                        <td>
                                            <ul class="list-inline">
                                            @forelse($user->skills as $skill)
                                            <li>{{$skill->name}}</li>
                                            @empty 
                                            <li class="people-info">@lang('peoples.noskills')</li> 
                                            @endforelse
                                            </ul>                                            
                                        </td>
                                    </tr>
                                    @if(count($user->teams))
                                    <tr>
                                        <td><b>@lang('peoples.teams')</b></td>
                                        <td>
                                            <table class="table table-responsive">
                                                <tbody>
                                                @foreach($user->teams as $team)
                                                    <tr>
                                                        <td style="width: 50%">
                                                            <a href="{{ route('teams.show', $team->team) }}">{{$team->team->name}}</a>
                                                        </td>
                                                        <td style="width: 25%">
                                                            {{$team->position}}
                                                        </td>
                                                        <td>
                                                            {{\Carbon\Carbon::parse($team->created_at)->format('d M. Y')}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>                                            
                                        </td>
                                    </tr>
                                    @endif

                                    @if(count($user->organizations))
                                        <tr>
                                            <td><b>Организации</b></td>
                                            <td>
                                                <table class="table table-responsive">
                                                    <tbody>
                                                    @foreach($user->organizations as $organization)
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <a href="{{ route('organizations.show', $organization->organization) }}">{{$organization->organization->name}}</a>
                                                            </td>
                                                            <td style="width: 25%">
                                                                {{$organization->position}}
                                                            </td>
                                                            <td>
                                                                {{\Carbon\Carbon::parse($organization->created_at)->format('d M. Y')}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td><b>@lang('peoples.company')</b></td>
                                        <td></td>
                                    </tr>    
                                </tbody>
                            </table>
                                                        
                            @if(auth()->user()->id != $user->id)
                                <form action="{{route('threads.store')}}" method="post">
                                    {{csrf_field()}}
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-envelope" aria-hidden="true"></i> @lang('peoples.message')
                                    </button>
                                </form>
                            @endif                                



                            <h3 style="border-bottom: 1px solid rgba(34, 36, 38, .15);">@lang('peoples.feedbacks') </h3>
                            <div class="row">
                                @forelse($user->comments as $comment)
                                    <div class="col-md-9">
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <div class="author">{{$comment->author->username}}</div>
                                                <div class="metadata">
                                                    <span class="date">{{\Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i')}}</span>

                                                    @if($comment->rating == 'positive')
                                                        <span class="text-success">@lang('peoples.positive')</span>
                                                    @endif

                                                    @if($comment->rating == 'negative')
                                                        <span class="text-success">@lang('peoples.negative')</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="media-text text-justify">{{$comment->body}}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-md-9">
                                        <div class="media-body">
                                            <div class="media-text text-justify">@lang('peoples.nofeedbacks')</div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection