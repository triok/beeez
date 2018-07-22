@foreach($job->jobs as $subJob)
    <tr>
        <td>{{$subJob->name}}</td>
        <td>{{$subJob->access}}</td>
        <td>{{ \Carbon\Carbon::parse($subJob->end_date)->format('d M, Y H:i') }}</td>
        <td>{{$subJob->formattedPrice}}</td>
        <td><span class="label label-warning">{{$subJob->status}}</span></td>
        <td><a href="{{route('jobs.show', $subJob)}}"><i class="fa fa-link"></i></a></td>
    </tr>
@endforeach