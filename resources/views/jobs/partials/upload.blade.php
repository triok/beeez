<hr>

<div class="row file-upload">
    <div class="col-md-12">
        {!! Form::open([ 'route' => ['files.upload'], 'files' => true, 'enctype' => 'multipart/form-data', 'class' =>
        'dropzone', 'id' => 'dropzone' ]) !!}

        <div>
            <h3>Upload Multiple Files By Click On Box</h3>
        </div>

        {!! Form::close() !!}
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