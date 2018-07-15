<{{$tag}} class="media">
    <div class="media-left">
        <a href="javascript:void(0);">
            <img class="media-object img-rounded" src="{{$comment->author->getStorageDir() . $comment->author->avatar}}" alt="{{$comment->author->name}}">
        </a>
    </div>
    <div class="media-body">
        <div class="media-heading">
            <div class="author">{{$comment->author->name}}</div>
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
        <span class="comment-reply">
            <a href="#" class="reply">ответить</a>
        </span>
        </div>
        @foreach($comment->comments as $comment)
            @include('jobs.comment', ['tag' => 'div'])
        @endforeach
    </div>
</{{$tag}}>