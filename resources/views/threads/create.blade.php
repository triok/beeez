@extends('layouts.app')

@section('content')
    <div class="container" id="main">
        <div class="content-form">
            <h2><i class="fa fa-plus"></i> Add a group chat</h2>

            {!! Form::open(['url' => route('threads.store'), 'files' => true, 'enctype' => 'multipart/form-data', 'id' => 'threads-form']) !!}
            <input type="hidden" name="thread_type" value="group">

            @include('threads/_form')

            @include('threads/_users')

            <hr>

            <div class="btn-toolbar" id="savesubmit">
                <div class="btn-group btn-group-lg">
                    <button type="submit" class="btn btn-primary" id="submit" name="submit" value="submit">
                        @lang('organizations.submit')
                    </button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@include('partials.summer',['editor'=>'.editor1'])