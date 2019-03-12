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
                @if($proposal->proposal_type)
                    Команда <a href="{{ route('teams.show', $proposal->team) }}">{{ $proposal->team->name }}</a>
                @else
                    {{$proposal->user->username}}
                @endif
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
                @if(auth()->user()->payerCard())
                    <form action="{{route('proposals.apply', [$job, $proposal])}}" method="post">
                        {{csrf_field()}}
                        <button class="btn btn-primary btn-sm">
                            Принять предложение
                        </button>
                    </form>
                @else
                    <button class="btn btn-primary btn-sm" disabled>
                        Принять предложение
                    </button>
                    <p><small>Необходимо сначала добавить карту для <a href="{{route('account')}}#bill">оплаты работы</a>.</small></p>
                @endif
            </div>
        @endif
    </div>
</li>