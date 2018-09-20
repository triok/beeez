@if($count = Auth::user()->newThreadsCount())
    <span class="label label-danger">{{ $count }}</span>
@endif