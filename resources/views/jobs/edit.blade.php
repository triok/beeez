@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @include('jobs.partials.navigation')

        <div class="col-sm-9" id="main">
            <div class="base-wrapper"> 
            @include('jobs.partials.tabs')

            @if(isset($job))
                {!! Form::model($job,['url'=>route('jobs.update',$job->id),'method'=>'patch']) !!}
            @else
                {!! Form::open(['url'=>'/jobs', 'files' => true, 'enctype' => 'multipart/form-data']) !!}
            @endif

            <div class="tab-content">
                @include('jobs.partials.form')
            </div>

            <div class="job-row savesubmit" id="savesubmit">
                <button type="submit" class="btn btn-primary" id="submit" name="submit"
                        value="submit">@lang('edit.submit')</button>

                <button type="submit" class="btn btn-primary" id="draft" name="draft"
                        value="save">@lang('edit.save')</button>
            </div>

            {!! Form::close() !!}

            @include('jobs.partials.upload')
            </div>
        </div>
    </div>
@endsection

@include('partials.summer',['editor'=>'.editor1'])

<!-- @include('partials.tinymce',['editor'=>'.editor2']) -->

@include('jobs.partials.modal-categories')

@stack('styles')

@push('styles')
    <link href="/plugins/dropzone/dropzone.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plugins/bootstrap-select/bootstrap-select.min.css"/>
    <link rel="stylesheet" href="/css/custom.css"/>
    <link rel="stylesheet" href="/css/datepicker.min.css"/>
@endpush

@push('scripts')
    <script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="/js/custom.js"></script>
    <script src="/js/datepicker.min.js"></script>
    <script>
        $('.timepicker-actions').datepicker({
            timepicker: true,
            startDate: new Date(),
            minHours: 9,
            maxHours: 24,
            onSelect: function (fd, d, picker) {
                if (!d) return;

                picker.update({
                    minHours: 0,
                    maxHours: 24
                })
            }
        });

        function truncate(text, length){
            return text.length > length ? text.slice(0, length) + '...' : text;
        };

        var x;

        function getUsers(task_id, value) {
            var result = $("#task-" + task_id).find('.search').find('.result');

            result.show().html('<li>Подождите ...</li>').fadeIn();

            if (x) x.abort();

            x = $.ajax({
                url: "/api/users/search",
                data: {name: value},
                type: 'GET',
                success: function success(response) {
                    if (response.length == 0) {
                        result.html('<li>Пользователь не найден!</li>');

                        return false;
                    }

                    var res = '';

                    response.data.forEach(function (entry) {
                        res += '<li onclick="setUser(' + task_id + ', ' + entry.id + ', \'' + entry.name + '\');">' +
                            '<a href="#" onclick="return false;">' + entry.name + '</a></li>';
                    });

                    result.html(res);
                },
                error: function error(xhr, status, error) {}
            });
        }

        function setUser(task_id, user_id, user_name) {
            $("#task-" + task_id).find('.search').find('.result').html('').hide();

            $("#task-" + task_id + " .search-user").val(user_id);
            $("#task-" + task_id + " .search-input").val(user_name);
        }

        var user_search = '';

        $("#task-1 .search-input").focus(function () {
            getUsers(1, $(this).val())
        });

        $("#task-1 .search-input").keyup(function () {
            if(user_search !== $(this).val()) {
                user_search = $(this).val();

                getUsers(1, $(this).val())
            }
        });

        $("#task-1 .search-input").on('blur', function (e) {
            setTimeout(function () {
                $("#task-1").find('.search').find('.result').html('').hide();
            }, 2000);
        });
    </script>
@endpush