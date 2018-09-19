@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <ul class="nav nav-tabs">
      <li role="presentation" class="active"><a data-toggle="tab" href="#panel1">@lang('application.freelancer')</a></li>
      <li role="presentation"><a data-toggle="tab" href="#panel2">@lang('application.client')</a></li>
      <li role="presentation"><a data-toggle="tab" href="#panel3">@lang('application.titlebookmarks')</a></li>
    </ul>
    <div class="tab-content">
        <div id="panel1" class="tab-pane fade in active">
            <div class="col-xs-12" id="main">
                <h2>@lang('application.titlefreelancer')</h2>


                @if (count($applications) > 0)
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <td>@lang('application.date')</td>
                        <td>@lang('application.enddate')</td>            
                        <td>@lang('application.job')</td>
                        <td>@lang('application.status')</td>
                        <td></td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($applications as $application)
                        <tr>
                            <td>{{\Carbon\Carbon::parse($application->created_at)->format('d M, Y')}}</td>
                            <td>{{\Carbon\Carbon::parse($application->job->end_date)->format('d M, Y, H:i')}}</td>                
                            <td><a href="{{route('jobs.show', $application->job)}}" id="{{$application->job->id}}">{{$application->job->name}}</a></td>
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
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                    {!! $applications->links() !!}
                @else
                @lang('application.nojobs')
                @endif

                <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="home-tab">@lang('application.nojobs')</div>


            </div>
        </div>
        <div id="panel2" class="tab-pane fade ">
            <div id="panel1" class="tab-pane fade in active">
                <div class="col-xs-12" id="main">
                <h2>@lang('application.titleclient')</h2>
                @if (count($clientapps) > 0)   
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <td>@lang('application.job')</td>
                        <td>@lang('application.date')</td>
                        <td>@lang('application.enddate')</td>            
                        <td>@lang('application.status')</td>
                    </tr>
                    </thead>
                    <tbody>
 
                    @foreach($clientapps as $clientapp)
                    <tr>
                        <td><a href="{{route('jobs.show', $clientapp)}}" id="{{$clientapp->id}}">{{$clientapp->name}}</a></td>
                        <td>{{\Carbon\Carbon::parse($clientapp->created_at)->format('d M, Y')}}</td>
                        <td>{{\Carbon\Carbon::parse($clientapp->end_date)->format('d M, Y, H:i')}}</td>
                        <td>
                            <button data-id="{{$clientapp->id}}" {!! $clientapp->status == config('enums.jobs.statuses.IN_REVIEW') ? 'disabled' : '' !!} class="btn btn-success btn-sm btn-review">
                            <i class="fa fa-handshake-o" aria-hidden="true"></i>
                            @lang('home.complete')
                            </button>
                        </td>
                        <td>
                            @if($clientapp->status == config('enums.jobs.statuses.DRAFT'))
                                <a href="{{route('jobs.edit', $clientapp)}}">Edit</a>
                            @endif
                        </td>                                                
                    </tr>                    
                    @endforeach
                    
                    </tbody>
                </table>
                @else
                @lang('application.nojobs')                     
                @endif

                </div>
            </div>
        </div>
        <div id="panel3" class="tab-pane fade ">
            <div id="panel1" class="tab-pane fade in active">
                <div class="col-xs-12" id="main">
                <h2>@lang('application.titlebookmarks')</h2>
                @lang('application.noBookmarks')
                </div>
            </div>                
        </div>
        </div>

                   


</div>    
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