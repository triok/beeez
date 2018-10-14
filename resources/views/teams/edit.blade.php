@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="content-form team-edit">
        <h2><i class="fa fa-pencil"></i> {{ $team->name }}</h2>

        <div class="row">
            <div class="col-md-6">
                    <div class="form-group">
                        <label for="input-team-type">@lang('teams.team_type_edit')</label>
                        <span class="team-info">{{ $team->type->name }}</span>
                    </div>
            </div>
        </div>

        {!! Form::open(['url' => route('teams.update', $team), 'files' => true, 'enctype' => 'multipart/form-data', 'method'=>'patch']) !!}



        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="input-logo">@lang('teams.logo_edit')</label><br>

                    <img src="{{ $team->logo() }}"
                         class="img-thumbnail"
                         alt="{{ $team->name }}"
                         title="{{ $team->name }}"
                         style="width: 100px; height: 100px;margin-bottom: 5px;">

                    <input type="file" name="logo" id="input-logo">
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="input-description">@lang('teams.description_edit')</label>
                    {!! Form::textarea('description', old('description', isset($team) ? $team->description : ''), ['class' => 'editor1', 'id' => 'input-description']) !!}
                </div>
            </div>
        </div>

        @include('teams/_users')

        <hr>

        <div class="btn-toolbar" id="savesubmit">
            <div class="btn-group btn-group-md">
                <button type="submit" class="btn btn-primary" id="submit" name="submit"
                        value="submit">@lang('teams.save')</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>    
@endsection

@include('partials.summer',['editor'=>'.editor1'])