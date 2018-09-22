<div class="media">
    <a class="pull-left" href="#" style="padding-right: 20px;">
        <img src="{{$message->user->getStorageDir() . $message->user->avatar}}"
             class="img-thumbnail"
             style="width: 60px; height: 60px;">
    </a>

    <div class="pull-right">
        <small>Posted {{ $message->created_at->diffForHumans() }}</small>
    </div>

    <div class="media-body">
        <b>{{ $message->user->name }}</b>

        <p style="padding-top: 7px;">{{ $message->body }}</p>

        @if($files = $message->files)
            @foreach($files as $file)
                <div>
                    <a target="_blank" href="{{ $file->link() }}" style="font-size: 11px;">
                        {{ $file->title }}
                    </a>
                </div>
            @endforeach
        @endif
    </div>
</div>