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
                <p>Актуально до: {{ Carbon\Carbon::parse($proposal->up_to)->format('d.m.Y H:i') }}</p>
                @if ($proposal->up_to < Carbon\Carbon::now())
                <button id="up_to" data-title="{{$job->name}}" class="btn btn-primary confirm-job-btn " title="Просрочено" disabled>
                    <i class="fa fa-handshake" aria-hidden="true"></i>Просрочено
                </button>
                @else
                <button id="{{ $proposal->proposal_type ? $proposal->team->name : $proposal->user->username }}" data-title="{{$job->name}}" class="btn btn-primary confirm-job-btn " title="Принять предложение">
                    <i class="fa fa-handshake" aria-hidden="true"></i>Принять предложение 
                </button>                
                @endif
            </div>

        @endif

    </div>
</li>

@push('modals')
    <div class="modal fade" id="confirmJobModal-{{ $proposal->user->username}}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Готовы к сделке?</h4>
                </div>
                <form action="{{route('proposals.apply', [$job, $proposal])}}" method="post">
                    {{csrf_field()}}                
                    <div class="modal-body">
                        <p>Вы выбрали исполнителем пользователя: 
                            <span></span>
    <!--                         @if($proposal->proposal_type)
                                Команда <a href="{{ route('teams.show', $proposal->team) }}">{{ $proposal->team->name }}</a>
                            @else
                                {{$proposal->user->username}}
                            @endif -->
                        </p>
                        <p>Пожалуйста, укажите итоговую стоимость задания</p>
                        <p><input type="text" name="amount" value="{{$job->price}}" /></p>
                    </div>    
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm">
                            Принять предложение
                        </button>
                    </div>                                
                </form>                    
            </div>
        </div>
    </div>
@endpush