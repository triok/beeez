@extends('layouts.app')

@section('content')
    <div class="container create-project" id="main">
        <div class="col-xs-6">
        <h2>@lang('vacancies.title_new_vacancy')</h2>

        <hr>

        {!! Form::open(['url' => route('organizations.vacancies.store', $organization)]) !!}
        <input type="hidden" name="publish" id="publish" value="0">

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    {!! Form::text('name', old('name'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Название вакансии']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    {!! Form::textarea('responsibilities', old('responsibilities'), ['required'=>'required', 'class'=>'form-control', 'rows' => '4', 'placeholder' => 'Обязанности']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    {!! Form::textarea('requirements', old('requirements'), ['required'=>'required', 'class'=>'form-control', 'rows' => '4', 'placeholder' => 'Требования']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    {!! Form::textarea('conditions', old('conditions'), ['class'=>'form-control', 'rows' => '4', 'placeholder' => 'Условия']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="specialization">Специализация</label>

                    <select class="form-control" required id="specialization" name="specialization">
                        <option value=""></option>
                        @foreach(config('vacancy.specializations') as $specialization)
                            @if($specialization == old('specialization'))
                                <option selected value="{{ $specialization }}">
                                    @lang('vacancies.specialization_' . $specialization)
                                </option>
                            @else
                                <option value="{{ $specialization }}">
                                    @lang('vacancies.specialization_' . $specialization)
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <hr>

        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="submit" class="btn btn-primary" name="button" value="submit">@lang('vacancies.button_create')</button>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-default" name="button" value="publish">@lang('vacancies.button_publish')</button>
            </div>
        </div>
        
        {!! Form::close() !!}
        </div>
        <div class="col-xs-6">
        <div class="base-wrapper">
        <h2>Ваше объявление будет выглядеть так:</h2>
        <input type="text" name="vacancy">
        </div>          
        </div>
    </div>
@endsection