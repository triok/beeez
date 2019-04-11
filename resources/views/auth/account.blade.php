@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <h2>@lang('account.title')</h2>
    <div class="row">
        <div class="col-md-3">
            <img src="{{$user->getStorageDir() . $user->avatar}}" class="img-thumbnail" alt="{{$user->name}}" title="{{$user->name}}" style="width: 260px; height: 260px;">
            <ul class="nav nav-tabs nav-pills nav-stacked" role="tablist">
                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab"
                                                          data-toggle="tab">@lang('account.home')</a></li>

                <li role="presentation">
                    <a href="#bio" aria-controls="bio" role="tab" data-toggle="tab">@lang('account.bio')</a>
                </li>
                <li role="presentation">
                    <a href="#examples" aria-controls="examples" role="tab" data-toggle="tab">@lang('account.jobs')</a>
                </li>
                <li role="presentation">
                    <a href="#experience" aria-controls="experience" role="tab" data-toggle="tab">@lang('account.experience')</a>
                </li>
                <li role="presentation">
                    <a href="#bill" aria-controls="profile" role="tab" data-toggle="tab">@lang('account.bill')</a>
                </li>
                <li role="presentation">
                    <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">@lang('account.profile')</a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home">

                    <div class="row">
                        <div class="col-xs-12">
                          
                            <ul class="list-unstyled test">
                            <li><span>@lang('account.login')<b>{{$user->username}}</b></span></li>
                            <li><span>@lang('account.name')<b>{{$user->name}}</b></span></li>
                            <li><span>@lang('account.member')<b>{{$user->registeredOn}}</b></span></li>
                            <li><span>@lang('account.email')<b>{{$user->email}}</b></span></li>   
                 
                            <li>
                                <span>
                                    @lang('account.team')
                                    @forelse($user->teams as $team)
                                    <b><a href="{{ route('teams.show', $team->team) }}">{{$team->team->name}}</a></b>
                                    @empty 
                                    <b>@lang('account.noteams')</b>
                                    @endforelse
                                </span>
                            </li>
                            <li>
                                <span>
                                    @lang('account.organization')
                                    @forelse($user->organizations as $organization)
                                    <b><a href="{{ route('organizations.show', $organization->organization) }}">{{$organization->organization->name}}</a></b>
                                    @empty 
                                    <b>@lang('account.noorg')</b>
                                    @endforelse
                                </span>
                            </li>
                            <li><span>@lang('account.status')<b> @lang('account.notapproved')</b></span></li>
                            </ul> 
                        </div>
                    </div>
                    <hr/>

                </div>

                <div role="tabpanel" class="tab-pane fade" id="bio">
                    @include('auth.bio')
                </div>

                <div role="tabpanel" class="tab-pane fade" id="examples">
                    @include('auth.examples')
                </div>
                
                <div role="tabpanel" class="tab-pane fade" id="experience">
                    @include('auth.experience')
                </div>

                <div role="tabpanel" class="tab-pane fade" id="bill">
                    @include('auth.bill')
                </div>

                <div role="tabpanel" class="tab-pane fade" id="profile">
                    @include('auth.profile')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection