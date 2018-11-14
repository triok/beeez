{{--{!! Form::open(['url'=>'account/bio','method'=>'post', 'file'=>'true']) !!}--}}
<form action="/account/bio" method="post" enctype="multipart/form-data">
{{csrf_field()}}
<label>Avatar</label><br>
<img src="{{$user->getStorageDir() . $user->avatar}}" class="img-thumbnail" alt="{{$user->name}}" title="{{$user->name}}" style="width: 100px; height: 100px;">

<input type="file" name="avatar" id="avatar">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Tell us about yourself</label>
                {!! Form::textarea('bio',$user->bio,['class'=>'form-control','rows'=>3,'required'=>'required']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="input-speciality">Специализация</label>
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
            </div>
        </div>
    </div>

<label>Skills</label>
{!! Form::text('skills',null,['id'=>'skills','class'=>'form-control']) !!}
@foreach($user->skills as $skill)
    <span class="label label-default">{{$skill->name}} &nbsp; <i id="{{$skill->id}}" class="fa fa-times delete-my-skill" style="cursor:pointer;"></i> </span>
@endforeach
<br/>
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
<br>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-default">Update</button>
    </div>
</div>
</form>
{{--{!! Form::close() !!}--}}

@include('partials.tokeninput',['elem'=>'skills','path'=>'/skills-json'])