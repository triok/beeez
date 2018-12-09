@extends('layouts.app')

@section('content')
    <div class="container-fluid applications">
        <div class="col-xs-8">
            <div class="base-wrapper">
            @include('applications.partials.nav-tabs')

            <div class="tab-content">
                @include('applications.partials.freelancer')

                @include('applications.partials.client')

                @include('applications.partials.favorite')
            </div>
            </div>
        </div>
        <div class="col-xs-4">
            
                @include('applications.partials.info')
            
        </div>
    </div>
@endsection

@push('styles')
    <link href="/css/custom.css" rel="stylesheet">
@endpush

@push('scripts')
    <script>
        $('.show-remarks').click(function () {
            var app = $(this).attr('id');
            $('#app_r_' + app).toggle('slow');
            $('#app_ar_' + app).toggle('slow');
        });
    </script>
    <script src="/js/custom.js"></script>
@endpush

@push('modals')
    <div class="modal fade" id="completeJobForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">You can add some comment or files.</h4>
                </div>

                <form action="" method="post" enctype="multipart/form-data" id="form-complete">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <textarea name="message" rows="3" class="form-control" placeholder="Enter an optional message"
                                  required></textarea>
                        <input type="file" multiple name="files[]">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary btn-sm "><i class="fa fa-send"></i> Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush