@extends('layouts.app')

@section('content')
    <router-view></router-view>
@stop

@section('users')
    <div class="col-xs-6 col-sm-2 sidebar-offcanvas" id="sidebar" role="navigation"></div>
@stop

@push('scripts')
<script>
    $(document).ready(function () {
        $('#file-upload').on('change', uploadMessageFile);

        var element = document.getElementById("messages");

        if (element) {
            element.scrollTop = element.scrollHeight;
        }
    });

    function uploadMessageFile(event) {
        var data = new FormData();

        data.append('file', event.target.files[0]);

        $.ajax({
            url: '/api/upload',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            before: function () {
                $('#file-upload').val();
            },
            success: function (data, textStatus, jqXHR) {
                if (data.status == 'success') {
                    addMessageFile(data.data.title, data.data.path);
                } else {
                    alert(data.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('ERRORS: ' + textStatus);
            }
        });
    }

    var message_num = 0;

    function addMessageFile(title, path) {
        var template = '<tr><td><input type="hidden" name="files[' + message_num + '][path]" value="' + path + '">';

        template += '<input type="hidden" name="files[' + message_num + '][title]" value="' + title + '">' + title + '</td>';

        template += '<td class="text-right"><button type="button" onclick="$(this).parent().parent().remove();" class="btn btn-danger btn-sm"><i aria-hidden="true" class="fa fa-close"></i></button></td></tr>';

        $('#table-files').find('tbody').append(template);

        message_num++;
    }
</script>
@endpush