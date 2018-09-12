@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="content-form">
        <h2><i class="fa fa-pencil"></i> {{ $organization->name }}</h2>

        {!! Form::open(['url' => route('organizations.update', $organization), 'files' => true, 'enctype' => 'multipart/form-data', 'method'=>'patch']) !!}

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="input-logo">@lang('organizations.logo')</label><br>

                    <img src="{{ $organization->logo() }}"
                         class="img-thumbnail"
                         alt="{{ $organization->name }}"
                         title="{{ $organization->name }}"
                         style="width: 100px; height: 100px;margin-bottom: 5px;">

                    <input type="file" name="logo" id="input-logo">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="input-description">@lang('organizations.description')</label>
                    {!! Form::textarea('description', old('description', isset($organization) ? $organization->description : ''), ['class' => 'editor1', 'id' => 'input-description']) !!}
                </div>
            </div>
        </div>

        @if($organization->is_approved)
            @include('organizations/_users')
        @endif

        <hr>

        <div class="btn-toolbar" id="savesubmit">
            <div class="btn-group btn-group-lg">
                <button type="submit" class="btn btn-primary" id="submit" name="submit"
                        value="submit">@lang('organizations.submit')</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>    
@endsection

@include('partials.summer',['editor'=>'.editor1'])