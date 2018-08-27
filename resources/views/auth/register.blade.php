@extends('layouts.app')

@section('content')
<div class="container" id="main">
    <div class="row">
        <div class="col-md-8">
            <h2>@lang('auth.register-title')</h2>
            <div class="panel panel-default">
                <div class="panel-body">
                    @if(env('ALLOW_REGISTRATION'))
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">@lang('auth.name')</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">@lang('auth.email')</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">@lang('auth.login')</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" required>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">@lang('auth.password')</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">@lang('auth.confirm-password')</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('auth.register-button')
                                </button>
                            </div>
                        </div>
                    </form>
                        @else
                        <div class="alert alert-danger">Registration is not allowed at this time.</div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection
@push('scripts')
    <script>
        var timer;
        var x;
        var _token = $('meta[name="csrf-token"]').attr('content');
        var usernameTag = $("#username");
        var parent = usernameTag.closest('.form-group');

        usernameTag.keyup(function () {
            var _this = $(this);
            var username = _this.val();

            if (username.length > 2) {
                if (x) { x.abort() } // If there is an existing XHR, abort it.
                clearTimeout(timer); // Clear the timer so we don't end up with dupes.
                timer = setTimeout(function() { // assign timer a new timeout
                    x = $.ajax({
                        url: "{{route('register')}}",
                        data: { _token: _token, username: username },
                        type: 'POST',
                        async: true,
                        success: function success(response) {
                            parent.removeClass('has-error');
                            parent.find('.help-block').remove();
                        },
                        error: function error(xhr, status, error) {
                            var errors = JSON.parse(xhr.responseText);

                            parent.addClass('has-error');
                            _this.after("<span class=\"help-block\"><strong>" + errors.username + "</strong></span>");
                            //notice(errors.username, 'error');
                        },
                    }); // run ajax request and store in x variable (so we can cancel)
                }, 1000); // 1000ms delay, tweak for faster/slower
            }
        });
    </script>
@endpush
