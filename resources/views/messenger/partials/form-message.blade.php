<hr>

<h2>@lang('messages.add')</h2>

<form action="{{ route('messages.update', $thread->id) }}" method="post" id="send-message">
    {{ method_field('put') }}
    {{ csrf_field() }}

    <div class="form-group">
        <textarea name="message" id="message" required rows="3" class="form-control"></textarea>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive" id="table-files">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">@lang('messages.send')</button>

        <div style="display: inline-block;padding-left: 10px;">
            <label for="file-upload" style="font-size: 12px;color: #47afa5;cursor: pointer;">
                Прикрепить файл
            </label>
            <input id="file-upload" type="file" style="display: none;">
        </div>
    </div>
</form>

@push('scripts')
    <script>
        $('#send-message input[type=file]').on('change', uploadFile);

        $(document).ready(function () {
            var element = document.getElementById("messages");
            element.scrollTop = element.scrollHeight;
        });

        function uploadFile(event) {
            var data = new FormData();

            data.append('file', event.target.files[0]);

            $.ajax({
                url: '{{ route('uploader') }}',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                before: function () {
                    $('#send-message input[type=file]').val();
                },
                success: function (data, textStatus, jqXHR) {
                    if (data.status == 'success') {
                        addFile(data.data.title, data.data.path);
                    } else {
                        alert(data.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('ERRORS: ' + textStatus);
                }
            });
        }

        var num = 0;

        function addFile(title, path) {
            var template = '<tr><td><input type="hidden" name="files[' + num + '][path]" value="' + path + '">';

            template += '<input type="hidden" name="files[' + num + '][title]" value="' + title + '">' + title + '</td>';

            template += '<td class="text-right"><button type="button" onclick="$(this).parent().parent().remove();" class="btn btn-danger btn-sm"><i aria-hidden="true" class="fa fa-close"></i></button></td></tr>';

            $('#table-files').find('tbody').append(template);

            num++;
        }
    </script>
@endpush