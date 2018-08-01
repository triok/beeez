@extends('layouts.app')

@section('content')
    <div class="content-form">
        <h2><i class="fa fa-pencil"></i> {{ $team->name }}</h2>

        {!! Form::open(['url' => route('teams.update', $team), 'files' => true, 'enctype' => 'multipart/form-data', 'method'=>'patch']) !!}

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="input-logo">@lang('teams.logo')</label><br>

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
                    <label for="input-description">@lang('teams.description')</label>
                    {!! Form::textarea('description', old('description', isset($team) ? $team->description : ''), ['class' => 'editor1', 'id' => 'input-description']) !!}
                </div>
            </div>
        </div>

        @include('teams/_users')

        <hr>

        <div class="btn-toolbar" id="savesubmit">
            <div class="btn-group btn-group-lg">
                <button type="submit" class="btn btn-primary" id="submit" name="submit"
                        value="submit">@lang('teams.submit')</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@include('partials.summer',['editor'=>'.editor1'])