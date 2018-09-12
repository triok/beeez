@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="content-form">
        <h2><i class="fa fa-plus"></i> @lang('organizations.new_organization')</h2>

        {!! Form::open(['url' => route('organizations.store'), 'files' => true, 'enctype' => 'multipart/form-data', 'id' => 'organization-form']) !!}

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="input-name">@lang('organizations.name')</label>
                    {!! Form::text('name', old('name'), ['required'=>'required', 'class'=>'form-control', 'id' => 'input-name']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="input-logo">@lang('organizations.logo')</label><br>

                    <img src="/storage/images/organizations/default.png"
                         class="img-thumbnail"
                         alt="Select logo"
                         title="Select logo"
                         style="width: 100px; height: 100px;margin-bottom: 5px;">

                    <input type="file" name="logo" id="input-logo">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="input-description">@lang('organizations.description')</label>
                    {!! Form::textarea('description', old('description'), ['class' => 'editor1', 'id' => 'input-description']) !!}
                </div>
            </div>
        </div>

        <hr>

        <div class="btn-toolbar" id="savesubmit">
            <div class="btn-group btn-group-lg">
                <button type="submit" class="btn btn-primary" id="submit" name="submit"
                        value="submit">@lang('organizations.submit')</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
<div>
@endsection

@include('partials.summer',['editor'=>'.editor1'])