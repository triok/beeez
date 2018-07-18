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
        </tr>
        </thead>
        <tbody>
        {{--{{dd($applications)}}--}}
        @foreach($applications as $application)
            <tr>
                <td>{{\Carbon\Carbon::parse($application->created_at)->format('d M, Y')}}</td>
                <td>{{$application->name}}</td>
                <td>{!! $application->prettyStatus !!}</td>
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
    })
</script>
@endpush