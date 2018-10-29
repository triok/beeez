@include('peoples.partials.actions-template')

<table class="table table-responsive table-full-width table-peoples table-hover table-search" id="users-table" style="width: 100%;">
    <thead>
    <tr>
        <th>@lang('peoples.login')</th>
        <th>@lang('peoples.name')</th>
        <th>@lang('peoples.feedbacks')</th>
        <th>@lang('peoples.member')</th>
        <th></th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#users-table').DataTable({
                bFilter: false,
                bInfo: false,
                "lengthChange": false,
                "pagingType": "numbers",
                "pageLength": 20,

                "ajax": {
                    "url": "{{ $action }}",
                    "dataSrc": "data"
                },
                "columns": [
                    {
                        "data": "username",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<a href="' + row.route + '">' + row.username + '</a>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "name",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return row.name;
                            }

                            return data;
                        }
                    },
                    {
                        "data": "rating_positive",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<span class="text-success">' + row.rating_positive + '</span>/<span class="text-danger">' + row.rating_negative + '</span>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "created_at",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<span class="date-short">' + moment(row.created_at, "YYYY-MM-DD mm:ss").format("ll") + '</span>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "id",
                        "bSortable": false,                        
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                var template = $('#actions-template').html();

                                template = template.replace(/#id/g, row.id);

                                if (row.is_favorited) {
                                    $('#form-unfavorite-' + row.id).attr('class', 'inline');
                                } else {
                                    $('#form-favorite-' + row.id).attr('class', 'inline');
                                }

                                return template;
                            }

                            return data;
                        }
                    },
                ]
            });
        });
    </script>
@endpush