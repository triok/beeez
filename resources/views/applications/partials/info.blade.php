<div class="application-info">
    <div class="base-wrapper">
        @lang('application.task-created') {{ count($clientapps) }}
        <i class="fa fa-tasks" aria-hidden="true"></i>
    </div>
    <div class="base-wrapper">
        @lang('application.task-completed') {{ count($appscomplete) }} 
        <i class="fa fa-handshake-o" aria-hidden="true"></i>
    </div>
    @if (isset($firstdeadline))
    @php($attr = $firstdeadline->getAttributes())
    <div class="base-wrapper">
        <p>@lang('application.first-deadline')</p>
        <p><a href="/jobs/{{ $firstdeadline->job->id }}">{{ $firstdeadline->job->name }}</a></p>
        <span>@lang('application.deadline-time')</span>
        <span class="date-full">{{ $attr['deadline'] }}</span>
        <i class="fa fa-fire" aria-hidden="true"></i>
    </div>    
    @endif
</div>


