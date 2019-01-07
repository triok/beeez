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
                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab"
                                           data-toggle="tab">@lang('account.profile')</a></li>
                <li role="presentation">
                    <a href="#bio" aria-controls="bio" role="tab" data-toggle="tab">@lang('account.bio')</a>
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
                                    @lang('account.about')
                                    @if($user->bio)
                                    <b>{{$user->bio}}</b>
                                    @else
                                    <b>Не заполнено</b>
                                    @endif
                                </span>
                            </li>
                            <li>
                                <span>
                                @lang('account.spec')
                                @if ($user->speciality)   
                                    <b>{{$user->speciality}}</b>
                                @else
                                    <b>@lang('account.nospec')</b> 
                                @endif
                                </span>
                            </li>
                            <li>
                                <ul class="list-inline" style="margin-left: unset;">
                                <span>@lang('account.skills')</span>
                                @forelse($user->skills as $skill)
                                <li>{{$skill->name}}</li>
                                @empty 
                                <li class="people-info"><b>@lang('account.noskills')</b></li> 
                                @endforelse
                                </ul>     
                            </li>
                            <li>
                                @lang('account.joblist')
                            </li>
                            <li><span>@lang('account.jobscomplete')<b> 0</b></span></li>
                            <li><span>@lang('account.jobscreated')<b> 0</b></span></li>                            
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
                            <li>
                                <span>@lang('account.lastwork')</span>
                                <span><a data-toggle="collapse" href="#lastwork" role="button" aria-expanded="false" aria-controls="lastWork">@lang('account.expand')</a></span>
                                <div class="collapse" id="lastwork">
                                  <div class="card card-body">
                                    <ul class="list-unstyled">
                                        <li>
                                            <table class="table">
                                                <thead>
                                                    <th>Вступил</th>
                                                    <th>Вышел</th>
                                                    <th>Организация/команда</th>
                                                    <th>Должность</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>20.02.2016</td>
                                                        <td>04.04.2017</td>
                                                        <td>StoneProduct</td>
                                                        <td>Менеджер</td>
                                                    </tr>
                                                     <tr>
                                                        <td>10.12.2017</td>
                                                        <td>20.12.2018</td>
                                                        <td>ИП Иванов И.И.</td>
                                                        <td>Дизайнер</td>
                                                    </tr>                                                   
                                                </tbody>
                                            </table>
                                        </li>
                                    </ul>
                                  </div>
                                </div>
                            </li>
                            <li>
                                <span>@lang('account.schedule')</span>
                                    @if($user->show_working_hours)
                                        @php($working_hours = $user->working_hours ? json_decode($user->working_hours, true) : [])
                                        <tr>
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
                                                <p class="pull-right"><i class="fa fa-globe" aria-hidden="true"></i> <small >Указано местное время</small></p>
                                                <div class="clearfix"></div>
                                            </td>
                                        </tr>
                                    @endif                                
                            </li>
                            <li>
                                <span>@lang('account.social')</span>
                                @php($socialCount = 0)
                                @if(isset($user->socLinks))
                                    @foreach($user->socLinks as $social)
                                        @if(isset($social['obj']) && $social['obj']->pivot->status == config('tags.statuses.confirmed.value'))
                                        {{$social['title']}}<li class="list-group-item list-group-item-success">{{$social['obj']->pivot->link}}<span class="badge">confirmed</span></li>
                                        @php($socialCount++)
                                        @endif
                                    @endforeach
                                @endif
                                @if(!$socialCount)
                                    <span class="people-info"><b>@lang('peoples.nosocial')</b></span> 
                                @endif   
                            </li>
                            <li><span>@lang('account.status')<b> @lang('account.notapproved')</b></span></li>
                            <li><span>@lang('account.experience')<b> @lang('account.notexp')</b></span></li>
                            <li><span>@lang('account.examples')<b> @lang('account.noexamples')</b></span></li>

                            </ul> 
                        </div>
                    </div>
                    <hr/>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="profile">
                    {{--{!! Form::open(['url'=>'account/bio','method'=>'post', 'file'=>'true']) !!}--}}
                    <form action="/account/bio" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="base-wrapper">
                        <label>Avatar</label><br>
                        <img src="{{$user->getStorageDir() . $user->avatar}}" class="img-thumbnail" alt="{{$user->name}}" title="{{$user->name}}" style="width: 100px; height: 100px;">
                        <input type="file" name="avatar" id="avatar">

                        {!! Form::model($user,['url'=>'/account/profile', 'method' => 'patch']) !!}
                        <label>@lang('account.name')</label>
                        {!! Form::text('name',null,['class'=>'form-control','required'=>'required']) !!}
                        <label>@lang('account.email')</label>
                        {!! Form::input('email','email',null,['class'=>'form-control','required'=>'required']) !!}
                        <label>@lang('account.about')</label>
                        {!! Form::textarea('bio', $user->bio,['class'=>'form-control','rows'=>11, 'id' => 'text-counter']) !!}
                        <p class="pull-right"><small>Кол-во символов: </small><small id="counter"></small><small>/2000</small></p>
                    </div>
                    <div class="base-wrapper">
                        <label for="input-speciality">@lang('account.spec')</label>
                        <select class="form-control" name="speciality" id="input-speciality">
                            <option value="">Нет</option>
                            @foreach(config('enums.account.specialities') as $speciality)
                                @if($speciality == $user->speciality)
                                    <option selected value="{{ $speciality }}">@lang('account.speciality.' . $speciality)</option>
                                @else
                                    <option value="{{ $speciality }}">@lang('account.speciality.' . $speciality)</option>
                                @endif
                            @endforeach
                        </select> 

                        <label>@lang('account.joblist')</label>
                        {!! Form::input('joblist','joblist',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="base-wrapper">
                        <label for="input-country">@lang('account.country')</label>
                        {!! Form::text('country', $user->country, ['class'=>'form-control', 'id' => 'input-country']) !!}
                        <label for="input-city">@lang('account.city')</label>
                        {!! Form::text('city', $user->city, ['class'=>'form-control', 'id' => 'input-city']) !!}                    
                        @include('auth.working-hours')
                    </div>
                    <div class="base-wrapper">
                        <label>@lang('account.social')</label>
                        <div>
                        @foreach($user->socLinks as $key => $socLink)

                            <label for="{{$key}}">{{$socLink["title"]}}</label>
                            <div class="row">

                                <div class="col-md-8">
                                    <input type="text" name="{{$key}}" id="{{$key}}" class="form-control input-val" value="{{isset($socLink['obj']) ? $socLink['obj']->pivot->link : null}}"
                                            {{isset($socLink['obj']) && ($socLink['obj']->pivot->status == config('tags.statuses.confirmed.value') || $socLink['obj']->pivot->status == config('tags.statuses.verified.value')) ? 'disabled' : null }} >
                                </div>
                                <div class="col-md-4">

                                    <div class="btn-group pull-right" role="group" aria-label="...">
                                        @if(isset($socLink['obj']) && $socLink['obj']->pivot->status == config('tags.statuses.confirmed.value'))
                                            <button type="button" class="btn btn-success social-btn-conf" disabled>Confirmed</button>
                                        @elseif(isset($socLink['obj']) && $socLink['obj']->pivot->status == config('tags.statuses.verified.value'))
                                            <button type="button" class="btn btn-warning social-btn-conf" disabled>Verified</button>
                                        @else
                                            <button type="button" class="btn btn-info social-btn-save">Save</button>
                                            <button type="button" class="btn btn-success social-btn-conf" {{ (isset($socLink['obj']) ? '' : 'disabled') }}>Confirm</button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                        </div>
                    <p>
                        <span><b>@lang('account.status')</b>  @lang('account.notapproved')</span>
                        <button class="btn btn-info">@lang('account.approve')</button>
                    </p>
                    <p>
                        <span><b>@lang('account.experience')</b>  @lang('account.notexp')</span>
                        <button class="btn btn-info">Заполнить и подтвердить</button>
                    </p>
                    </div>
                    <div class="base-wrapper">
                    <p>
                        <span><b>@lang('account.examples')</b>  @lang('account.noexamples')</span>
                        <button class="btn btn-info">Загрузить примеры работ</button>
                    </p>
                    </div>

    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-default">Update</button>
        </div>
    </div>
</form>
{{--{!! Form::close() !!}--}}


@push('scripts')
<script>
$(function() {
    $(document).ready(function() {
        var $textarea = '#text-counter'; 
        var $counter = '#counter';
        $($counter).html( $($textarea).val().length );
        $($textarea).on('blur, keyup', function() {
            var $max = 2000; // Максимальное кол-во символов
            var $val = $(this).val();
            $(this).attr('maxlength', $max); // maxlength=""
            if( $val.length <= 0 ) {
                $($counter).html(0);
            } else {
                if( $max < parseInt( $val.length ) ) {
                    $this.val( $val.substring(0, $max) ); 
                }
                $($counter).html( $(this).val().length );
            }
        });
  });
});
</script>
@endpush
                    <hr/>
                    <h4>@lang("Password")</h4>
                    <div class="callout callout-warning">
                        <label>@lang('auth.new-password')</label>
                        {!! Form::input('password','password',null,['class'=>'form-control']) !!}
                        <label>@lang('auth.confirm-password')</label>
                        {!! Form::input('password','password_confirmation',null,['class'=>'form-control']) !!}
                    </div>
                    <br/>
                    <button class="btn btn-default"><i class="fa fa-save"></i> @lang('Update')</button>
                    {!! Form::close() !!}
                </div>
                <div role="tabpanel" class="tab-pane fade" id="bio">
                    @include('auth.bio')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection