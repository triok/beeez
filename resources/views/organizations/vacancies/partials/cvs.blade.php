@if($organization->user_id == auth()->id() && $vacancy->cvs->count())
    <h2>Отклики</h2>

    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>Логин</th>
                    <th>Дата отклика</th>
                    <th>Статус</th>
                    <th class="text-right">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vacancy->cvs as $cv)
                    <tr>
                        <td>{{ $cv->user->name }}</td>
                        <td class="date-short">{{ $cv->created_at }}</td>
                        <td>
                            @lang('cvs.' . $cv->status)

                            @if($cv->answered_at)
                                (<span class="date-short">{{ $cv->answered_at }}</span>)
                            @endif
                        </td>
                        <td class="text-right">
                            @if($cv->status == 'pending')
                                {!! Form::open(['url' => route('vacancies.cvs.approve', [$vacancy, $cv]), 'method'=>'post', 'class' => 'inline']) !!}
                                <button type="submit" class="btn btn-sm btn-success">
                                    Принять
                                </button>
                                {!! Form::close() !!}

                                {!! Form::open(['url' => route('vacancies.cvs.reject', [$vacancy, $cv]), 'method'=>'post', 'class' => 'inline']) !!}
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Оклонить
                                </button>
                                {!! Form::close() !!}
                            @endif

                            <button onclick="$('#modal-cv-{{ $cv->id }}').modal();" class="btn btn-default btn-sm" title="Инфнормация об отклике">
                                <i class="fa fa-info" aria-hidden="true"></i>
                            </button>

                            {!! Form::open(['url' => route('threads.store'), 'method'=>'post', 'class' => 'inline']) !!}
                            <input type="hidden" name="user_id" value="{{ $organization->user_id }}">
                            <button class="btn btn-primary btn-sm" title="Чат с соискателем">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </button>
                            {!! Form::close() !!}

                            {!! Form::open(['url' => route('vacancies.cvs.destroy', [$vacancy, $cv]), 'method'=>'delete', 'class' => 'inline form-delete']) !!}
                            <button type="submit" class="btn btn-sm btn-danger" title="Удалить">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

    @foreach($vacancy->cvs as $cv)
        @include('organizations.vacancies.partials.modal-cv-info')
    @endforeach
@endif