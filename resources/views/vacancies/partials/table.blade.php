@include('vacancies.partials.actions-template')

<table class="table table-responsive table-full-width table-peoples table-hover table-search" id="{{ $id }}-vacancies-table"
       style="width: 100%;">
    <thead>
    <tr>
        <th>@lang('vacancies.col_name')</th>
        <th>@lang('vacancies.col_organization')</th>
        <th>@lang('vacancies.col_specialization')</th>
        <th>@lang('vacancies.col_published')</th>
        <th>@lang('vacancies.col_actions')</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#{{ $id }}-vacancies-table').DataTable({
                bFilter: false,
                bInfo: false,
                "lengthChange": false,

                "pageLength": 20,

                "ajax": {
                    "url": "{{ $action }}",
                    "dataSrc": "data"
                },

                "columns": [
                    {
                        "data": "name",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<a href="' + row.route + '">' + row.name + '</a>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "organization.name",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<a href="' + row.organization.route + '">' + row.organization.name + '</a>';
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
                                return '<span class="date-short">' + moment(data, "YYYY-MM-DD mm:ss").format("ll") + '</span>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                var template = $('#vacancy-actions-template').html();

                                template = template.replace(/#slug/g, row.id);
                                template = template.replace(/#id/g, '{{ $id }}-' + row.id);

                                if (row.is_favorited) {
                                    $('#vacancy-form-unfavorite-{{ $id }}-' + row.id).attr('class', 'inline');
                                } else {
                                    $('#vacancy-form-favorite-{{ $id }}-' + row.id).attr('class', 'inline');
                                }

                                if(!row.is_added_cv) {
                                    $('#cv-link-{{ $id }}-' + row.id).prop("disabled", false);
                                }

                                return template;
                            }

                            return data;
                        }
                    },
                ]
            });

            $('#vacancy-type-filter').on('change', function () {
                table.ajax.url("{{ $action }}&specialization=" + $(this).val()).load();
            });
        });
    </script>
@endpush