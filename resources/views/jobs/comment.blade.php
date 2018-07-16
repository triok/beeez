<{{$tag ?? 'li'}} class="media" data-id="{{$comment->id}}">
    <div class="media-left">

        <img class="media-object img-rounded" src="{{$comment->author->getStorageDir() . $comment->author->avatar}}" alt="{{$comment->author->name}}">

    </div>
    <div class="media-body">
        <div class="media-heading">
            <div class="author">{{$comment->author->username}}</div>
            <div class="metadata">
                <span class="date">{{\Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i')}}</span>
            </div>
        </div>
        <div class="media-text text-justify">{{$comment->body}}</div>
        <div class="footer-comment">
        {{--<span class="vote plus" title="Нравится">--}}
          {{--<i class="fa fa-thumbs-up"></i>--}}
        {{--</span>--}}
            {{--<span class="rating">--}}
          {{--+1--}}
        {{--</span>--}}
            {{--<span class="vote minus" title="Не нравится">--}}
          {{--<i class="fa fa-thumbs-down"></i>--}}
        {{--</span>--}}
            {{--<span class="devide">--}}
         {{--|--}}
        {{--</span>--}}
        @if(($job->user_id === auth()->id() || auth()->user()->hasRole('admin')) && (auth()->id() != $comment->author->id))
        <span class="comment-reply">
            <a href="javascript:void(0);" class="reply">ответить</a>
        </span>
        @endif
        </div>
        @foreach($comment->comments as $comment)
            @include('jobs.comment', ['tag' => 'div'])
        @endforeach
    </div>
</{{$tag ?? 'li'}}>