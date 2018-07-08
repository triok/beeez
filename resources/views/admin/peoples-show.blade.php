@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <hr>
            <a href="{{route('peoples.index')}}"><span><i class="fa fa-arrow-left"></i> back to list</span></a>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{$user->getStorageDir() . $user->avatar}}" class="img-thumbnail" alt="{{$user->name}}" title="{{$user->name}}" style="width: 100px; height: 100px;">
                        </div>
                        <div class="col-md-8">
                            <p><b>Login:</b> <span>{{$user->username}}</span></p>
                            <p><b>Date registration:</b> <span>{{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y') }}</span></p>
                            <p><b>Social links:</b></p>
                            <div class="row">
                                <div class="col-md-9">
                                    <ul class="list-group">
                                        @foreach($user->socialLinks as $social)
                                            @if(isset($social['obj']) && $social['obj']->pivot->status == config('tags.statuses.confirmed.value'))
                                                {{$social['title']}}<li class="list-group-item list-group-item-success">{{$social['obj']->pivot->link}}<span class="badge">confirmed</span></li>
                                            @else
                                                {{$social['title']}}<li class="list-group-item list-group-item-warning">{{isset($social['obj']) ? $social['obj']->pivot->link : null}}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection