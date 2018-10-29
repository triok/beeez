<div id="client" class="tab-pane fade">
    <div class="col-xs-12">
        <h2>@lang('application.titleclient')</h2>

        <table class="table table-responsive table-full-width table-search" id="client-table" style="width: 100%;">
            <thead class="thead-purple">
            <tr>
                <th>@lang('application.job')</th>
                <th>@lang('application.date')</th>
                <th>@lang('application.enddate')</th>
                <th>@lang('application.status')</th>
                <th></th>
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
            $('#client-table').DataTable({
                bFilter: false,
                bInfo: false,
                "lengthChange": false,
                "pagingType": "numbers",
                order: [[1, 'desc']],
                "pageLength": 20,

                "oLanguage": {
                    "sZeroRecords": "Заданий нет.",
                },
                
                "ajax": {
                    "url": "/api/v1/jobs/search?user_id=" + auth_id,
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
                    },
                    {
                        "data": "status",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                var isDisabled = '';

                                if (row.status == 'in review') {
                                    isDisabled = 'disabled';
                                }

                                return '<button data-id="' + row.id + '" ' + isDisabled + ' class="btn btn-success btn-sm btn-review button-round" >' +
                                    '<i class="fa fa-handshake-o" aria-hidden="true"></i> Завершить</button>';
                            }

                            return data;
                        }
                    },
                    {
                        "data": "id",
                        "bSortable": false,
                        "render": function (data, type, row, meta) {
                            if (type === 'display' && row.status == 'draft') {
                                return '<form action="/jobs/' + row.id + '/edit"><button class="btn btn-primary btn-xs button-round" type="submit"><i class="fa fa-pencil"></i></button></form>';
                            }

                            return '';
                        }
                    },
                ]
            });
        });
    </script>
@endpush