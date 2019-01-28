{{--{!! Form::open(['url'=>'account/bio','method'=>'post', 'file'=>'true']) !!}--}}
<form action="/account/bio" method="post" enctype="multipart/form-data">
{{csrf_field()}}


    <div class="row">
        <div class="base-wrapper">
            <p>
            <label>Avatar</label><br>
            <img src="{{$user->getStorageDir() . $user->avatar}}" class="img-thumbnail" alt="{{$user->name}}" title="{{$user->name}}" style="width: 100px; height: 100px;">

            <input type="file" name="avatar" id="avatar">
            </p>
            <p>
            <label>@lang('account.name')</label>
            {!! Form::text('name',$user->name,['class'=>'form-control','required'=>'required']) !!}
            </p>
            <p>
            <label>@lang('account.email')</label>
            {!! Form::input('email','email',$user->email,['class'=>'form-control','required'=>'required']) !!}
            </p>
            <p>
            <label>Расскажите о себе</label>
            {!! Form::textarea('bio', $user->bio,['class'=>'form-control','rows'=>11, 'id' => 'text-counter']) !!}
            <p class="pull-right"><small>Кол-во символов: </small><small id="counter"></small><small>/2000</small></p>
            </p>                 
        </div>
    </div>
    <div class="row">
        <div class="base-wrapper">
            <p>
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
            </p>

            <div id="skills-list" style="position: relative;margin-bottom: 20px;">
                <label>@lang('account.skills')</label>
                {!! Form::text('skills',null,['id'=>'skills','class'=>'form-control']) !!}
            </div>

            <p>
            <label>@lang('account.joblist')</label>
            <div class="form-inline">
            <div class="form-group">
                {!! Form::input('joblist','joblist',null,['class'=>'form-control']) !!} <button type="button" class="btn btn-primary"><i aria-hidden="true" class="fa fa-plus"></i></button>
            </div>
            </div>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="base-wrapper">
            <p>
            <label for="input-country">@lang('account.country')</label>
            {!! Form::text('country', $user->country, ['class'=>'form-control', 'id' => 'input-country']) !!}
            </p>
            <p>
            <label for="input-city">@lang('account.city')</label>
            {!! Form::text('city', $user->city, ['class'=>'form-control', 'id' => 'input-city']) !!}
            </p>
            <p>@include('auth.working-hours')</p>                  
        </div>
    </div>
    <div class="row">
        <div class="base-wrapper">
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
    </div> 





    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-default">@lang('account.save')</button>
        </div>
    </div>
</form>
{{--{!! Form::close() !!}--}}

@include('partials.tokeninput',['elem'=>'skills','path'=>'/skills-json','elements'=>$user->skills])

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