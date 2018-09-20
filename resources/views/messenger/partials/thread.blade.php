<?php $class = $thread->isUnread(Auth::id()) ? 'alert-info' : ''; ?>

<div class="media alert {{ $class }}" style="margin: 0;padding-top: 0">
    <a class="pull-left" href="{{ route('messages.show', $thread->id) }}">
        <img src="{{ $thread->avatar() }}"
             class="img-thumbnail"
             style="width: 40px; height: 40px;display: block;margin: 0 auto;">
    </a>

    <h4 class="media-heading" style="padding-top: 10px;">
        <a href="{{ route('messages.show', $thread->id) }}">{{ $thread->title() }}</a>
        ({{ $thread->userUnreadMessagesCount(Auth::id()) }} unread)
    </h4>
</div>