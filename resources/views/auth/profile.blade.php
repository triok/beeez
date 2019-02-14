<div class="row">
    <div class="col-md-12">
        <div class="base-wrapper">
            {!! Form::model($user,['url'=>'/account/profile', 'method' => 'patch']) !!}
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
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="base-wrapper">
            <h2>@lang('account.status')</h2>
            <p>@lang('account.' . auth()->user()->approved)</p>

            @if(auth()->user()->approved == 'not_approved')
                <button type="button" class="btn btn-primary" onclick="$(this).addClass('hide');$('#form').removeClass('hide');">
                    Загрузить паспорт
                </button>

                <form action="/account/approve" method="post" enctype="multipart/form-data" class="hide" id="form">
                    {{csrf_field()}}

                    <hr>

                    <div class="form-group">
                        <div class="form-group">
                            <input type="file" name="passports[]">
                        </div>
                        <div class="form-group">
                            <input type="file" name="passports[]">
                        </div>
                    </div>

                    <button class="btn btn-primary">
                        @lang('account.send')
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
