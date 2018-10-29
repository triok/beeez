@extends('layouts.app')

@section('content')
    <div class="container" id="main">
        <div class="row">
            <div class="col-md-12 vacancies-show">
                <a href="{{ route('organizations.vacancies.index', $organization) }}">
                    <i class="fa fa-arrow-left"></i>
                    @lang('vacancies.button_back')
                </a>

                <h2>{{ $vacancy->name }}</h2>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-responsive table-search">
                            <tr>
                                <td><b>@lang('vacancies.show_published')</b></td>
                                <td>
                                    @if($vacancy->published_at)
                                        <span class="date-short">{{ $vacancy->published_at }}</span>
                                    @else
                                        <span>Не опубликовано</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td><b>@lang('vacancies.show_organization')</b></td>
                                <td>
                                    <a href="{{ route('organizations.show', $vacancy->organization) }}">
                                        {{ $vacancy->organization->name }}
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td><b>@lang('vacancies.show_responsibilities')</b></td>
                                <td>{{ $vacancy->responsibilities }}</td>
                            </tr>

                            <tr>
                                <td><b>@lang('vacancies.show_conditions')</b></td>
                                <td>{{ $vacancy->conditions }}</td>
                            </tr>

                            <tr>
                                <td><b>@lang('vacancies.show_requirements')</b></td>
                                <td>{{ $vacancy->requirements }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection