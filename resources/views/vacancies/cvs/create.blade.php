@extends('layouts.app')

@section('content')
    <div class="container create-project" id="main">
        <h2>Откликнуться на вакансию</h2>

        <hr>

        {!! Form::open(['url' => route('vacancies.cvs.store', $vacancy), 'files' => true, 'enctype' => 'multipart/form-data', ]) !!}

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('name', old('name'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'ФИО']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('email', old('email'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Email']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('phone', old('phone'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Телефон']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::textarea('about', old('about'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Расскажите о себе']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="input-file">
                        Ваше резюме или примеры работ
                    </label>

                    <input type="file" name="cv" id="input-file">
                </div>
            </div>
        </div>

        <hr>

        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="submit" class="btn btn-primary" name="button" value="submit">
                    Отправить
                </button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection