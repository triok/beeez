@if($count = Auth::user()->unreadMessagesCount())
    <span class="label label-danger">{{ $count }}</span>
@endif