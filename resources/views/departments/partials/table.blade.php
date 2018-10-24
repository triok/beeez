@include('teams.partials.actions-template')

<table class="table table-responsive table-full-width table-peoples table-hover table-search" id="teams-table" style="width: 100%;">
    <thead>
    <tr>
        <th>@lang('teams.team')</th>
        <th>@lang('teams.owner')</th>
        <th>@lang('teams.show_team_type')</th>
        <th>@lang('teams.show_date')</th>
        <th>@lang('teams.actions')</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#teams-table').DataTable({
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
                                var res = '<a href="' + row.route + '">' + row.name + '</a>';

                                if (row.is_owner) {
                                    res += ' <i class="fa fa-star"></i>'
                                }

                                return res;
                            }

                            return data;
                        }
                    },
                    {
                        "data": "owner.name",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<a href="' + row.owner.route + '">' + row.owner.name + '</a>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "type",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return data;
                            }

                            return data;
                        }
                    },
                    {
                        "data": "created_at",
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
                                var template = $('#team-actions-template').html();

                                template = template.replace(/#id/g, row.id);

                                template = template.replace(/#slug/g, row.slug);

                                if (row.is_favorited) {
                                    $('#team-form-unfavorite-' + row.id).attr('class', 'inline');
                                } else {
                                    $('#team-form-favorite-' + row.id).attr('class', 'inline');
                                }

                                return template;
                            }

                            return data;
                        }
                    },
                ]
            });

            $('#team-type-filter').on('change', function () {
                table.ajax.url("{{ $action }}&type=" + $(this).val()).load();
            });
        });
    </script>
@endpush