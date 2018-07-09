@extends('layouts.app')

@section('content')
    <div class="row">
        @include('admin.settings-nav')
        <div class="col-md-9">
            <p class="description small">
                Member since: {{$user->registered_on}}
            </p>

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a>
                </li>
                <li role="presentation">
                    <a href="#bio" aria-controls="bio" role="tab" data-toggle="tab">Bio</a>
                </li>
                <li role="presentation">
                    <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Update Profile</a>
                </li>
                <li role="presentation">
                    <a href="#roles" aria-controls="roles" role="tab" data-toggle="tab">Roles</a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home">
                    {{--@include('auth.bio')--}}
                    {!! Form::open(['url'=>'account/bio','method'=>'post']) !!}

                    <label>Tell us about yourself</label>
                    {!! Form::textarea('bio',$user->bio,['class'=>'form-control','rows'=>3,'required'=>'required']) !!}

                    <label>Skills</label>
                    {!! Form::text('skills',null,['id'=>'skills','class'=>'form-control']) !!}
                    @foreach($user->skills as $skill)
                        <span class="label label-default">{{$skill->name}} &nbsp; <i id="{{$skill->id}}" class="fa fa-times delete-my-skill" style="cursor:pointer;"></i> </span>
                    @endforeach

                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-default">Update</button>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    @include('partials.tokeninput',['elem'=>'skills','path'=>'/skills-json'])

                </div>
                <div role="tabpanel" class="tab-pane" id="bio">
                    <h4>Bio</h4>
                    {{$user->bio}}
                    <hr/>
                    <h4>Skills</h4>
                    @foreach($user->skills as $skill)
                        <span class="label label-default">{{$skill->name}}</span>
                    @endforeach
                    <hr/>
                    @foreach($socials as $link)
                        <label for="{{$link->slug}}">{{$link->title}}</label>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="{{$link->slug}}" id="{{$link->slug}}" class="form-control input-val" value="{{$link->pivot->link}}">
                            </div>
                            <div class="col-md-4">
                                @if($link->pivot->status == config('tags.statuses.confirmed.value'))
                                    <button type="button" class="btn btn-success" disabled>Confirmed</button>
                                @else
                                    <button type="button" class="btn btn-success social-confirmed" data-user="{{$user->id}}">Confirm this link</button>
                                @endif
                            </div>
                        </div>
                        <br>
                    @endforeach
                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                    {!! Form::model($user,['url'=>'/users/'.$user->id.'/update']) !!}
                    <label>Name:</label>
                    {{Form::text('name',null,['required'=>'required','class'=>'form-control'])}}

                    <label>Email:</label>
                    {{Form::input('email','email',null,['required'=>'required','class'=>'form-control'])}}
                    <label>Password <em class="label label-default">if changing</em></label>
                    {!! Form::input('password','password',null,['class'=>'form-control']) !!}
                    <label>Confirm password</label>
                    {!! Form::input('password','password_confirmation',null,['class'=>'form-control']) !!}
                    <br/>
                    {{Form::submit('Update',['class'=>'btn btn-primary'])}}
                    {!! Form::close() !!}
                </div>

                <div role="tabpanel" class="tab-pane fade" id="roles">
                    <h3>Roles</h3>
                    {!! Form::open(['url'=>'/users/'.$user->id.'/roles', 'files' => true]) !!}

                    <table class="table table-responsive">
                        @foreach($roles as $role)
                            <tr>
                                <td>
                                    {{Form::radio('role',$role->id,(int)$currentRole==(int)$role->id)}}
                                </td>
                                <td valign="middle">{{ucwords($role->name)}}</td>
                                <td>
                                    @foreach($role->permissions as $rp)
                                        <span class="label label-default"> {{$rp->name}}</span>
                                    @endforeach
                                </td>
                            </tr>

                        @endforeach
                    </table>
                    <br/>
                    <button class="btn btn-default">Update</button>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>

@endsection