@extends('layouts.app')

@section('content')
<div class="col-xs-3 vacancies">
    <div class="base-wrapper">
        <h2>@lang('vacancies.title')</h2>
        <p>@lang('vacancies.title-note')</p>
    </div>
    <div class="base-wrapper">
    <div class="filter">

                    <div class="search input-gorup">
                        <input class="form-control" id="vacancy_search" placeholder="@lang('vacancies.search_name')">
                        <ul class="result"></ul>
                    </div>


        <h3>@lang('vacancies.filter_specialization')</h3>
        <ul class="list-unstyled">
            @foreach(config('vacancy.specializations') as $specialization)
            <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">
                    @lang('vacancies.specialization_' . $specialization)
                  </label>
                </div>                
            </li>
            @endforeach
        </ul>
        <h3>@lang('vacancies.filter_skills')</h3>
        <ul class="list-unstyled">
            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">PHP</label></div></li>
            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">VueJS</label></div></li>
            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">Ruby</label></div></li>
            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">Jango</label></div></li>
        </ul>
        <h3>@lang('vacancies.filter_salary')</h3>
        <ul class="list-unstyled">
            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">от 15 000 руб.</label></div></li>            
            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">от 30 000 руб.</label></div></li>
            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">от 50 000 руб.</label></div></li> 
            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">от 80 000 руб.</label></div></li>
            <li><div class="form-check"><input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                  <label class="form-check-label" for="defaultCheck1">от 100 000 руб.</label></div></li>                                   
        </ul>
    </div>
    </div>    
</div>
<div class="col-xs-9">
    <div class="base-wrapper" id="main">
        <div class="row">
            <div class="col-md-12 vacancies">



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