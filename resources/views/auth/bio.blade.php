{!! Form::open(['url'=>'account/bio','method'=>'post']) !!}

<label>Tell us about yourself</label>
{!! Form::textarea('bio',$user->bio,['class'=>'form-control','rows'=>3,'required'=>'required']) !!}

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
                    <button type="button" class="btn btn-success social-btn-conf" {{isset($socLink['obj']) ?: 'disabled'}}>Confirm</button>
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
{!! Form::close() !!}

@include('partials.tokeninput',['elem'=>'skills','path'=>'/skills-json'])