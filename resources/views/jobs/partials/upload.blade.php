<hr>

<div class="row file-upload">
    <div class="col-md-12">
        <div class="dropzone dz-clickable" id="dropzone{{ (isset($subtask) ? $subtask : '') }}">
            <h3>Upload Multiple Files By Click On Box</h3>
            <div class="dz-default dz-message">
                <span>Drop files here to upload</span>
            </div>
        </div>
    </div>
</div>

@if(isset($subtask))
    <script>
        $("#dropzone{{ $subtask }}").dropzone({
            maxFilesize: 5,
            addRemoveLinks: true,
            maxFiles: 10,
            parallelUploads: 1,
            url: "{{route('files.upload')}}?task_id={{ $task_id }}",
            headers: {
                'x-csrf-token': "{{ csrf_token() }}",
            },

            init: function () {
                this.on("addedfile", function (file) {
                    $.ajax({
                        type: 'POST',
                        url: "{{route('files.upload')}}",
                        data: {
                            task_id: "{{ $task_id }}",
                            file: file.name,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: 'html',
                    });
                });
            },
        });
    </script>
@endif