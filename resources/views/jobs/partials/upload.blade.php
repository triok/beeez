<hr>

<div class="row file-upload">
    <div class="col-md-12">
        <div class="dropzone" id="dropzone">
            <h3>Upload Multiple Files By Click On Box</h3>
        </div>
    </div>
</div>

@push('scripts')
    <script src="/plugins/dropzone/dropzone.js" type="text/javascript"></script>
    <script>
        var myDropzone = Dropzone.options.dropzone = {
            maxFilesize: 5,
            addRemoveLinks: true,
            maxFiles: 10,
            parallelUploads: 1,
            url: "{{route('files.upload')}}",

            init: function () {
                this.on("addedfile", function (file) {
                    $.ajax({
                        type: 'POST',
                        url: "{{route('files.upload')}}",
                        data: {file: file.name, _token: "{{ csrf_token() }}"},
                        dataType: 'html',
                    });
                });
            },
        };
    </script>
@endpush