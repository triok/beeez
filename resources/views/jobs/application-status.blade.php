<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title" id="myModalLabel">{{$app->job->name}}</h4>
</div>
<div class="modal-body">
    <table class="table table-responsive table-striped table-bordered">
        <tr>
            <td>Job:</td>
            <td colspan="3">{{$app->job->name}}</td>
        </tr>
        <tr>
            <td>Job Status:</td>
            <td>{{$app->status}}</td>
            <td>Payment Status:</td>
            <td>{!! $app->prettyStatus !!}</td>
        </tr>
        <tr>
            <td>End date:</td>
            <td>{{$app->job->end_date}}</td>
            <td>Price:</td>
            <td>{{$app->job->formattedPrice}}</td>
        </tr>

        <tr>
            <td>Remarks:</td>
            <td colspan="3">{{$app->remarks}}</td>
        </tr>
        <tr>
            <td>Access</td>
            <td colspan="3">{{$app->job->access}}</td>
        </tr>
        @if(isset($app->job->tag->value))
        <tr>
            <td>Tag</td>
            <td colspan="3"><a href="{{route('jobs.index')}}?tag={{$app->job->tag->value}}"><span class="label label-warning">{{$app->job->tag->value}}</span></a></td>
        </tr>
        @endif
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>