<div id="freelancer" class="tab-pane fade in active">
    <div class="col-xs-12">
        <h2>@lang('application.titlefreelancer')</h2>

        <table class="table table-striped table-responsive table-full-width table-search" id="freelancer-table" style="width: 100%;">
            <thead>
            <tr>
                <th>@lang('application.job')</th>
                <th>@lang('application.date')</th>
                <th>@lang('application.enddate')</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        var auth_id = "{{ auth()->id() }}";

        $(document).ready(function () {
            $('#freelancer-table').DataTable({
                bFilter: false,
                bInfo: false,
                "lengthChange": false,

                order: [[1, 'desc']],
                "pageLength": 20,

                "oLanguage": {
                    "sZeroRecords": "Заданий нет.",
                },

                "ajax": {
                    "url": "/api/v1/jobs/search?application=true&user_id=" + auth_id,
                    "dataSrc": "data"
                },

                "columns": [
                    {
                        "data": "name",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return '<a href="/jobs/' + row.id + '">' + row.name + '</a>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "created_at",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return moment(data, "YYYY-MM-DD mm:ss").format("ll");
                            }

                            return data;
                        }
                    },
                    {
                        "data": "end_date",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                return moment(data, "YYYY-MM-DD mm:ss").format("ll mm:ss");
                            }

                            return data;
                        }
                    }
                ]
            });
        });
    </script>
@endpush