<li class="media comment-box" data-id="{{$report->id}}">
    <div class="media-body">
        <div class="media-heading">
            <div class="author">
                {{$report->user->username}}
            </div>

            <div class="metadata">
                <span class="date">{{\Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i')}}</span>
            </div>
        </div>

        <div class="media-text text-justify">
            {{ $report->body }}
        </div>
    </div>
</li>