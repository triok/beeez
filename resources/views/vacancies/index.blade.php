@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-md-12 vacancies">
                <h2>@lang('vacancies.title')</h2>

                <div class="row">
                    <div class="col-md-2 search">
                        <input class="form-control" id="vacancy_search" placeholder="@lang('vacancies.search_name')">
                        <ul class="result"></ul>
                    </div>

                    <div class="col-md-2 search">
                        <select id="vacancy-type-filter" class="form-control border-bottom">
                            <option value="">@lang('vacancies.search_specialization')</option>

                            @foreach(config('vacancy.specializations') as $specialization)
                                <option value="{{ $specialization }}">
                                    @lang('vacancies.specialization_' . $specialization)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a data-toggle="tab" href="#all">@lang('vacancies.tab_vacancies')</a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#responses">@lang('vacancies.tab_responses')</a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#favorite">@lang('vacancies.tab_favorite')</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="all" class="tab-pane fade in active">
                        @include('vacancies.partials.table', ['id' => 'all', 'action' => '/api/vacancies/search?all=true'])
                    </div>

                    <div id="responses" class="tab-pane fade">
                        @include('vacancies.cvs.table')
                    </div>

                    <div id="favorite" class="tab-pane fade">
                        @include('vacancies.partials.table', ['id' => 'favorite', 'action' => '/api/vacancies/search?favorite=true'])
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush

@push('scripts')
    <script type="application/javascript">
        $("#vacancy_search").keyup(function () {
            var timer, x, value = $(this).val();

            if (value.length > 2) {
                if (x) x.abort();

                clearTimeout(timer); // Clear the timer so we don't end up with dupes.

                timer = setTimeout(function () {
                    x = $.ajax({
                        url: "/api/vacancies",
                        data: {q: value},
                        type: 'GET',
                        success: function success(response) {
                            $res = '';

                            if (response.length == 0) {
                                $(".result").html('<li>No results</li>').fadeIn();

                                return false;
                            }
                            response.forEach(function (entry) {
                                $res += '<li class=""><a href="/vacancies/' + entry.id + '">' + entry.name + '</a></li>';
                            });

                            $(".result").show().html($res).fadeIn();
                        }
                    });
                }, 1000);
            }

            $(".result").html('').hide();
        });

        $("#vacancy_search").on('blur', function (e) {
            setTimeout(function () {
                $(".result").html('').hide();
            }, 2000);
        });
    </script>
@endpush