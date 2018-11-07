@extends('layouts.app')

@section('content')
    <div class="container create-project" id="main">
        {!! Form::open(['url' => route('vacancies.cvs.success-store', [$vacancy, $cv]), 'files' => true, 'enctype' => 'multipart/form-data', ]) !!}

        @if($cv->status == 'approved')
            <h2>Как бы Вы хотели связаться с соискателем?</h2>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::text('name', old('name'), ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Ваше имя']) !!}
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
                        {!! Form::textarea('comment', old('comment'), ['class'=>'form-control', 'placeholder' => 'Комментарий']) !!}
                    </div>
                </div>
            </div>
        @endif

        @if($cv->status == 'declined')
            <h2>Вы отклоняете предложение, пожалуйста укажите причину (будет отправлена соискателю)</h2>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::textarea('comment', old('comment'), ['class'=>'form-control', 'placeholder' => '']) !!}
                    </div>
                </div>
            </div>
        @endif

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