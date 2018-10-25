@extends('layouts.app')

@section('content')
    <div class="container" id="main">
        <div class="content-form structure-edit">
            <h2>
                <i class="fa fa-pencil"></i>
                {{ $structure->name }}
                в <a href="{{ route('structure.index', $organization) }}"> {{ $organization->name }}</a>
            </h2>

            <div class="col-sm-2 pull-right">
                {!! Form::open(['url' => route('structure.destroy', [$organization, $structure]), 'method'=>'delete', 'class' => 'form-delete']) !!}
                <button type="submit" class="btn btn-danger btn-xs" title="@lang('structure.delete')">
                    <i class="fa fa-trash"></i> @lang('structure.delete')
                </button>
                {!! Form::close() !!}
            </div>

            {!! Form::open(['url' => route('structure.update', [$organization, $structure]), 'method'=>'patch']) !!}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input-name">@lang('structure.name')</label>
                        {!! Form::text('name', $structure->name, ['required'=>'required', 'class'=>'form-control', 'id' => 'input-name']) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input-description">@lang('structure.description')</label>
                        {!! Form::textarea('description', $structure->description, ['id' => 'input-description', 'class'=>'form-control', 'rows' => '5']) !!}
                    </div>
                </div>
            </div>

            @include('structures.partials.form_users')

            <hr>

            <div class="btn-toolbar" id="savesubmit">
                <div class="btn-group btn-group-md">
                    <button type="submit" class="btn btn-primary" id="submit" name="submit" value="submit">
                        @lang('structure.save')
                    </button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
