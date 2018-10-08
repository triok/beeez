@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-md-12">
                <h2>@lang('peoples.title')</h2>
                <div class="row">
                    <div class="col-md-3 search">
                        <input type="text" class="form-control" id="login_search" placeholder="@lang('peoples.search')"><i
                                class="fa fa-search" aria-hidden="true"></i>
                        <ul class="result"></ul>
                    </div>
                </div>
                <table class="table table-responsive table-full-width table-peoples table-hover table-search"
                       id="users-table">
                    <thead>
                    <tr>
                        <th>@lang('peoples.login')</th>
                        <th>@lang('peoples.name')</th>
                        <th>@lang('peoples.feedbacks')</th>
                        <th>@lang('peoples.member')</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#users-table').DataTable({
                bFilter: false,
                bInfo: false,
                "lengthChange": false,

                "pageLength": 20,
                "fnDrawCallback": function(oSettings) {
                    if ($('#users-table tr').length < 21) {
                        $('.dataTables_paginate').hide();
                    }
                },

                "ajax": {
                    "url": "api/v1/users",
                    "dataSrc": ""
                },
                "columns": [
                    {
                        "data": "username",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<a href="peoples/' + row.id + '">' + data + '</a>';
                            }

                            return data;
                        }
                    },
                    {"data": "name"},
                    {
                        "data": "rating_positive",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<span class="text-success">' + data + '</span>/<span class="text-danger">' + row.rating_negative + '</span>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "created_at",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<span class="date-short">' + data + '</span>';
                            }

                            return data;
                        }
                    },
                ]
            });
        });
    </script>
@endpush