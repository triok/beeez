@if($count = Auth::user()->unreadNotifications()->count())
    <span class="label label-danger">{{ $count }}</span>
@endif