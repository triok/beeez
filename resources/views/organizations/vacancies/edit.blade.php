@extends('layouts.app')

@section('content')
    <div class="container create-project" id="main">
        <h2>@lang('vacancies.title_edit_vacancy')</h2>

        <hr>

        {!! Form::open(['url' => route('organizations.vacancies.update', [$organization, $vacancy]), 'method'=>'patch']) !!}
        <input type="hidden" name="publish" value="0">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('name', $vacancy->name, ['required'=>'required', 'class'=>'form-control', 'placeholder' => 'Название вакансии']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('salary', $vacancy->salary, ['class'=>'form-control', 'placeholder' => 'Зарплата, руб.']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::textarea('responsibilities', $vacancy->responsibilities, ['required'=>'required', 'class'=>'form-control', 'rows' => '4', 'placeholder' => 'Обязанности']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::textarea('requirements', $vacancy->requirements, ['required'=>'required', 'class'=>'form-control', 'rows' => '4', 'placeholder' => 'Требования']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::textarea('conditions', $vacancy->conditions, ['class'=>'form-control', 'rows' => '4', 'placeholder' => 'Условия']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="specialization">Специализация</label>

                    <select class="form-control" required id="specialization" name="specialization">
                        <option value=""></option>
                        @foreach(config('vacancy.specializations') as $specialization)
                            @if($specialization == $vacancy->specialization)
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

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="input-skills">Навыки</label>

                    <select class="form-control selectpicker" id="input-skills" name="skills[]" multiple data-live-search="true">
                        <option value=""></option>
                        @foreach(\App\Models\Jobs\Skill::all() as $skill)
                            <option {{ (in_array($skill->id, $vacancy->skills()->pluck('skills.id')->toArray())) ? 'selected' : '' }}
                                    value="{{ $skill->id }}">

                                {{ $skill->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <hr>

        <div class="btn-toolbar">
            <div class="btn-group">
                <button type="submit" class="btn btn-primary" name="button" value="submit">@lang('vacancies.button_save')</button>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-default" name="button" value="publish">@lang('vacancies.button_publish')</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="/plugins/bootstrap-select/bootstrap-select.min.css"/>
@endpush

@push('scripts')
    <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
@endpush