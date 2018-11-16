@extends('layouts.app')

@section('content')
    <div class="container" id="main">
        <div class="row">
            <div class="col-md-12 vacancies-show">
                <a href="{{ route('vacancies.index') }}">
                    <i class="fa fa-arrow-left"></i>
                    @lang('vacancies.button_back')
                </a>

                <h2>{{ $vacancy->name }}</h2>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-responsive table-search">
                            <tr>
                                <td><b>@lang('vacancies.show_published')</b></td>
                                <td><span class="date-short">{{ $vacancy->published_at }}</span></td>
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

                        <hr>

                        <div class="btn-toolbar">
                            @if($vacancy->organization->user_id != auth()->id() && !$vacancy->cvs()->where('user_id', auth()->id())->count())
                                <a href="{{ route('vacancies.cvs.create', $vacancy) }}" class="btn btn-primary" style="margin-right: 5px;">
                                    @lang('vacancies.button_add_cv')
                                </a>
                            @else
                                <a href="{{ route('vacancies.cvs.create', $vacancy) }}" disabled class="btn btn-primary" style="margin-right: 5px;">
                                    @lang('vacancies.button_add_cv')
                                </a>
                            @endif

                            @if(!$vacancy->isFavorited())
                                {!! Form::open(['url' => route('vacancies.favorite', $vacancy), 'method'=>'post']) !!}
                                <button type="submit" class="btn btn-default" title="@lang('projects.favorite_add')">
                                    <i class="fa fa-star-o"></i>
                                </button>
                                {!! Form::close() !!}
                            @endif

                            @if($vacancy->isFavorited())
                                {!! Form::open(['url' => route('vacancies.unfavorite', $vacancy), 'method'=>'post']) !!}
                                <button type="submit" class="btn btn-default" title="@lang('projects.favorite_del')">
                                    <i style="color: orange;" class="fa fa-star"></i>
                                </button>
                                {!! Form::close() !!}
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection