@include('vacancies.partials.actions-template')

<table class="table table-responsive table-full-width table-peoples table-hover table-search" id="cvs-table"
       style="width: 100%;">
    <thead>
    <tr>
        <th>@lang('cvs.col_name')</th>
        <th>@lang('cvs.col_date')</th>
        <th>@lang('cvs.col_status')</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#cvs-table').DataTable({
                bFilter: false,
                bInfo: false,
                "lengthChange": false,

                "pageLength": 20,

                "ajax": {
                    "url": "/api/cvs/search",
                    "dataSrc": "data"
                },

                "columns": [
                    {
                        "data": "vacancy.name",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<a href="' + row.vacancy.route + '">' + row.vacancy.name + '</a>';
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
                        "data": "status",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return row.status;
                            }

                            return data;
                        }
                    },
                ]
            });
        });
    </script>
@endpush