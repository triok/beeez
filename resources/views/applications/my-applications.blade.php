@extends('layouts.app')
@section('content')
    <h2>@lang('application.title')</h2>
    <table class="table table-responsive">
        <thead>
        <tr>
            <td>@lang('application.date')</td>
            <td>@lang('application.job')</td>
            {{--<td>Offer</td>--}}
            <td>@lang('application.status')</td>
            <td></td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        {{--{{dd($applications)}}--}}
        @foreach($applications as $application)
            <tr>
                <td>{{\Carbon\Carbon::parse($application->created_at)->format('d M, Y')}}</td>
                <td>{{$application->job->name}}</td>
                <td>{!! $application->status == config('enums.jobs.statuses.IN_REVIEW') ? '<p class="label label-danger">Your task is under review</p>' : $application->prettyStatus !!}</td>
                <td>
                    <button data-id="{{$application->job->id}}" {!! $application->job->status == config('enums.jobs.statuses.IN_REVIEW') ? 'disabled' : '' !!} class="btn btn-success btn-sm btn-review">
                        <i class="fa fa-handshake-o" aria-hidden="true"></i>
                        @lang('home.complete')
                    </button>
                </td>
                <td>
                @if($application->status == config('enums.jobs.statuses.DRAFT'))
                    <a href="{{route('jobs.edit', $application->id)}}">Edit</a>
                @endif
                </td>
                {{--<td>--}}
                    {{--@if($application->status =="approved")--}}
                        {{--<a href="/job/{{$application->job->id}}/{{$application->id}}/work" class="btn btn-success btn-xs">Get started</a>--}}
                    {{--@else--}}
                        {{--@if(!empty($application->admin_remarks) ||  !empty($application->remarks))--}}
                            {{--<a href="#" id="{{$application->id}}" class="show-remarks">Remarks</a>--}}
                        {{--@endif--}}
                    {{--@endif--}}
                {{--</td>--}}
            </tr>
            {{--@if(!empty($application->remarks))--}}
                {{--<tr class="text-primary my-remarks small" id="app_r_{{$application->id}}"--}}
                    {{--style="display: none;background:#ddf2fc">--}}
                    {{--<td class="text-right"><strong>My Remarks:</strong></td>--}}
                    {{--<td colspan="3">--}}
                        {{--{{$application->remarks}}--}}
                    {{--</td>--}}
                {{--</tr>--}}
            {{--@endif--}}
            {{--@if(!empty($application->admin_remarks))--}}
                {{--<tr class="text-warning admin-remarks small" id="app_ar_{{$application->id}}"--}}
                    {{--style="display: none;background: #f9f0d7;">--}}
                    {{--<td class="text-right"><strong>Status Notes:</strong></td>--}}
                    {{--<td colspan="3">--}}
                        {{--{{$application->admin_remarks}}--}}
                    {{--</td>--}}
                {{--</tr>--}}
            {{--@endif--}}
        @endforeach
        </tbody>
    </table>
    {!! $applications->links() !!}
@endsection

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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">You can add some comment or files.</h4>
                </div>

                <form action="" method="post" enctype="multipart/form-data" id="form-complete">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <textarea name="message" rows="3" class="form-control" placeholder="Enter an optional message" required></textarea>
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