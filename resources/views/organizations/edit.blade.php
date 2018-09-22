@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="content-form">
        <h2><i class="fa fa-pencil"></i> {{ $organization->name }}</h2>

        {!! Form::open(['url' => route('organizations.update', $organization), 'files' => true, 'enctype' => 'multipart/form-data', 'method'=>'patch', 'id' => 'organization-form']) !!}

        @include('organizations/_form')

        @if($organization->status == 'approved')
            @include('organizations/_users')
        @endif

        <hr>

        <div class="form-group" id="savesubmit">
            <button type="submit" class="btn btn-primary" id="submit" name="submit"
                    value="submit">@lang('organizations.submit')
            </button>

            <div style="display: inline-block;padding-left: 10px;">
                <label for="file-upload" style="font-size: 12px;color: #47afa5;cursor: pointer;">
                    Прикрепить файл
                </label>
                <input id="file-upload" type="file" style="display: none;">
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>    
@endsection

@include('partials.summer',['editor'=>'.editor1'])