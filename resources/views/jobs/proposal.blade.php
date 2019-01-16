<li class="media comment-box" data-id="{{$proposal->id}}">
    <div class="media-body">
        <div class="media-heading">
            <div class="pull-right">
                @if(auth()->id() == $job->user_id)
                    <form action="{{route('threads.store')}}" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="user_id" value="{{$proposal->user->id}}">
                        <button class="btn btn-primary btn-sm" title="Отправить сообщение">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </button>
                    </form>
                @endif
            </div>

            <div class="author">
                @include('partials.online', ['user' => $proposal->user])
                {{$proposal->user->username}}
            </div>

            <div class="metadata">
                <span class="date">{{\Carbon\Carbon::parse($proposal->created_at)->format('d.m.Y H:i')}}</span>
            </div>
        </div>

        <div class="media-text text-justify">
            {{ $proposal->body }}
        </div>

        @if(auth()->id() == $job->user_id && !$job->applications->count())
            <div class="footer-comment">
                <form action="{{route('proposals.apply', [$job, $proposal])}}" method="post">
                    {{csrf_field()}}
                    <button class="btn btn-primary btn-sm">
                        Принять предложение
                    </button>
                </form>
            </div>
        @endif
    </div>
</li>