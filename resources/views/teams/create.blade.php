@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="content-form create-team">
        <h2><i class="fa fa-plus"></i> @lang('teams.new_team')</h2>
        <hr>
        {!! Form::open(['url' => route('teams.store'), 'files' => true, 'enctype' => 'multipart/form-data', 'id' => 'team-form']) !!}

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="input-name">@lang('teams.name')</label>
                    {!! Form::text('name', old('name'), ['required'=>'required', 'class'=>'form-control', 'id' => 'input-name']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="input-logo">@lang('teams.logo')</label><br>

                    <img src="/storage/images/teams/default.png"
                         class="img-thumbnail"
                         alt="Select logo"
                         title="Select logo"
                         style="width: 100px; height: 100px;margin-bottom: 5px;">

                    <input type="file" name="logo" id="input-logo">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="input-team-type">@lang('teams.team_type')</label>
                    <select name="team_type_id" id="input-team-type" required="required" class="form-control">
                        <option selected></option>
                        @foreach($teamTypes as $type)
                            @if($type->id == old('team_type_id'))
                                <option selected value="{{ $type->id }}">{{ $type->name }}</option>
                            @else
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="input-description">@lang('teams.description')</label>
                    {!! Form::textarea('description', old('description'), ['class' => 'editor1', 'id' => 'input-description']) !!}
                </div>
            </div>
        </div>

        @include('teams/_users')

        <hr>

        <div class="btn-toolbar" id="savesubmit">
            <div class="btn-group btn-group-md">
                <button type="submit" class="btn btn-primary" id="submit" name="submit"
                        value="submit">@lang('teams.submit')</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
<div>
@endsection

@include('partials.summer',['editor'=>'.editor1'])