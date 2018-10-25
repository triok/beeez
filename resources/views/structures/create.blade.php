@extends('layouts.app')

@section('content')
    <div class="container" id="main">
        <div class="content-form create-structure">
            <h2>
                <i class="fa fa-plus"></i>
                @lang('structure.new_structure')
                в <a href="{{ route('structure.index', $organization) }}"> {{ $organization->name }}</a>
            </h2>

            <hr>

            {!! Form::open(['url' => route('structure.store', $organization), 'id' => 'structure-form']) !!}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input-name">@lang('structure.name')</label>
                        {!! Form::text('name', old('name'), ['required'=>'required', 'class'=>'form-control', 'id' => 'input-name']) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input-description">@lang('structure.description')</label>
                        {!! Form::textarea('description', old('description'), ['id' => 'input-description', 'class'=>'form-control', 'rows' => '5']) !!}
                    </div>
                </div>
            </div>

            @include('structures.partials.form_users')

            <hr>

            <div class="btn-toolbar" id="savesubmit">
                <div class="btn-group btn-group-md">
                    <button type="submit" class="btn btn-primary" id="submit" name="submit" value="submit">
                        @lang('structure.submit')
                    </button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

