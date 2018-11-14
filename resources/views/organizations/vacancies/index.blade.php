@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-md-12 vacancies">
                <h2>
                    <a href="{{ route('organizations.show', $organization) }}">{{ $organization->name }}</a> -
                    @lang('vacancies.title')
                </h2>

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

                    <div class="col-md-8 search-button">
                        <a href="{{ route('organizations.vacancies.create', $organization) }}" class="btn btn-success btn-md pull-right">
                            <i class="fa fa-plus-circle"></i> @lang('vacancies.button_create_vacancy')
                        </a>
                    </div>
                </div>

                @include('organizations.vacancies.partials.table', ['id' => 'all'])
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="/js/custom.js"></script>
    <script type="application/javascript">
        $("#vacancy_search").keyup(function () {
            var timer, x, value = $(this).val();

            if (value.length > 2) {
                if (x) x.abort();

                clearTimeout(timer); // Clear the timer so we don't end up with dupes.

                timer = setTimeout(function () {
                    x = $.ajax({
                        url: "/api/vacancies?organization_id={{ $organization->id }}",
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