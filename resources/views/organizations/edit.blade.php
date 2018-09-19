@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="content-form">
        <h2><i class="fa fa-pencil"></i> {{ $organization->name }}</h2>

        {!! Form::open(['url' => route('organizations.update', $organization), 'files' => true, 'enctype' => 'multipart/form-data', 'method'=>'patch']) !!}

        @include('organizations/_form')

        @if($organization->status == 'approved')
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