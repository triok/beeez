@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="row">
        <div class="col-md-12">
            <a href="{{route('peoples.index')}}"><span><i class="fa fa-arrow-left"></i> @lang('peoples.back')</span></a>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{$user->getStorageDir() . $user->avatar}}" class="img-thumbnail" alt="{{$user->name}}" title="{{$user->name}}" style="width: 100px; height: 100px;">
                        </div>
                        <div class="col-md-8">
                            <p>
                                <b>@lang('peoples.login')</b> <span>{{$user->username}}</span>
                                (<span class="text-success">{{$user->rating_positive}}</span>/<span class="text-danger">{{$user->rating_negative}}</span>)
                            </p>
                            <p><b>@lang('peoples.name')</b> <span>{{$user->name}}</span></p>
                            <p><b>@lang('peoples.member')</b> <span>{{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y') }}</span></p>
                            <p><b>@lang('peoples.social')</b></p>
                            <div class="row">
                                <div class="col-md-9">
                                    <ul class="list-group">
                                    @if(!isset($user->socialLinks))
                                        @foreach($user->socialLinks as $social)
                                            @if(isset($social['obj']) && $social['obj']->pivot->status == config('tags.statuses.confirmed.value'))
                                                {{$social['title']}}<li class="list-group-item list-group-item-success">{{$social['obj']->pivot->link}}<span class="badge">confirmed</span></li>
                                            @endif
                                        @endforeach
                                    @else
                                        @lang('peoples.nosocial') 
                                    @endif   
                                    </ul>
                                </div>
                            </div>
                            <p><b>@lang('peoples.bio')</b></p>
                            <div class="row">
                                <div class="col-md-9">                            
                                    @if(isset($user->bio))
                                    <p>{{$user->bio}}</p>
                                    @else
                                    <p>@lang('peoples.nobio')</p>
                                    @endif
                                </div>
                            </div>
                            <p><b>@lang('peoples.skills')</b></p>
                            <div class="row">
                                <div class="col-md-9">
                                    <ul class="list-inline">
                                    @forelse($user->skills as $skill)
                                    <li>{{$skill->name}}</li>
                                    @empty 
                                    <li>No skills selected</li> 
                                    @endforelse
                                    </ul>
                                </div>    
                            </div>
                            <p><b>@lang('peoples.teams')</b></p>
                            <div class="row">
                                <div class="col-md-9">

                                </div>    
                            </div>
                            <p><b>@lang('peoples.company')</b></p>
                            <div class="row">
                                <div class="col-md-9">

                                </div>    
                            </div>                                                          
                            @if(auth()->user()->id != $user->id)
                                <form action="{{route('threads.store')}}" method="post">
                                    {{csrf_field()}}
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <button class="btn btn-primary btn-xs">
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