@include('organizations.vacancies.partials.actions-template')

<table class="table table-responsive table-full-width table-peoples table-hover table-search" id="vacancies-table"
       style="width: 100%;">
    <thead>
    <tr>
        <th>@lang('vacancies.col_name')</th>
        <th>@lang('vacancies.col_specialization')</th>
        <th>@lang('vacancies.col_published')</th>
        <th>@lang('vacancies.col_total_views')</th>
        <th>@lang('vacancies.col_total_responses')</th>
        <th>@lang('vacancies.col_actions')</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#vacancies-table').DataTable({
                bFilter: false,
                bInfo: false,
                "lengthChange": false,
                "pagingType": "numbers",
                "pageLength": 20,
                "language": {
                  "emptyTable": "Записей нет"
                },
                "ajax": {
                    "url": "/api/vacancies/search?all=true&organization_id={{ $organization->id }}",
                    "dataSrc": "data"
                },

                "columns": [
                    {
                        "data": "name",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<a href="' + row.organization.route + '/vacancies/' + row.id + '">' + row.name + '</a>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "specialization",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return data;
                            }

                            return data;
                        }
                    },
                    {
                        "data": "published_at",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                if(data) {
                                    return '<span class="date-short">' + moment(data, "YYYY-MM-DD mm:ss").format("ll") + '</span>';
                                } else {
                                    return 'Не опубликовано';
                                }
                            }

                            return data;
                        }
                    },
                    {
                        "data": "total_views",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return data;
                            }

                            return data;
                        }
                    },
                    {
                        "data": "total_responses",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return data;
                            }

                            return data;
                        }
                    },
                    {
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                var template = $('#vacancy-actions-template').html();

                                template = template.replace(/#route/g, row.organization.route);

                                template = template.replace(/#id/g, row.id);

                                if (!row.published_at) {
                                    $('#form-publish-' + row.id).attr('class', 'inline');
                                }

                                return template;
                            }

                            return data;
                        }
                    },
                ]
            });

            $('#vacancy-type-filter').on('change', function () {
                table.ajax.url("/api/vacancies/search?all=true&organization_id={{ $organization->id }}&specialization=" + $(this).val()).load();
            });
        });
    </script>
@endpush