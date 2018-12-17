<div class="air-card p-0-top-bottom">
    <div class="offers">
        <h2>@lang('show.reports')</h2>

        @if($job->reports->count())
            <ul class="media-list">
                @foreach($job->reports as $report)
                    @include('jobs.report')
                @endforeach
            </ul>
        @else
            @lang('show.noreports')
        @endif

        {!! Form::open(['url' => route('job.reports', $job), 'method'=>'post']) !!}

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <textarea required rows="5" name="body" class="form-control"></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">@lang('show.addreport')</button>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>