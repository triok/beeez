<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <img src="{{ $organization->logo() }}"
                     class="img-thumbnail"
                     alt="{{ $organization->name }}"
                     title=" {{$organization->name }}"
                     style="width: 100px; height: 100px;">
            </div>

            <div class="col-md-8">
                <p>
                    <b>Форма собственности:</b>
                    <span>
                        @if($organization->ownership == 'organization')
                            Организация
                        @endif

                        @if($organization->ownership == 'ip')
                            Индивидуальный предприниматель (ИП)
                        @endif
                    </span>
                </p>

                <p>
                    <b>Название организации:</b>
                    <span>{{ $organization->name }}</span>

                    @if(auth()->id() == $organization->user_id)
                        <i class="fa fa-star"></i>

                        @if($organization->status == 'moderation')
                            (<span class="text-warning">на модерации</span>)
                        @endif

                        @if($organization->status == 'rejected')
                            (<span class="text-danger">модерация провалена</span>)
                        @endif
                    @endif
                </p>

                <p>
                    <b>ОГРН:</b>
                    <span>{{ $organization->ohrn }}</span>
                </p>

                <p>
                    <b>ИНН:</b>
                    <span>{{ $organization->inn }}</span>
                </p>

                <p>
                    <b>КПП:</b>
                    <span>{{ $organization->kpp }}</span>
                </p>

                <p>
                    <b>Юридиеский адрес:</b>
                    <span>{{ $organization->address }}</span>
                </p>

                <p>
                    <b>Банк:</b>
                    <span>{{ $organization->bank }}</span>
                </p>

                <p>
                    <b>БИК:</b>
                    <span>{{ $organization->bik }}</span>
                </p>

                <p>
                    <b>Расчетный счет:</b>
                    <span>{{ $organization->bank_account }}</span>
                </p>

                <p>
                    <b>Корреспондентский счет:</b>
                    <span>{{ $organization->correspondent_account }}</span>
                </p>

                <p>
                    <b>Контактное лицо:</b>
                    <span>{{ $organization->contact_person }}</span>
                </p>

                <p>
                    <b>Ваш e-mail:</b>
                    <span>{{ $organization->email }}</span>
                </p>

                <p>
                    <b>Ваш телефон:</b>
                    <span>{{ $organization->phone }}</span>
                </p>

                <p>
                    <b>@lang('organizations.show_date')</b>
                    <span>{{ $organization->created_at->format('d M, Y') }}</span>
                </p>

                <b>@lang('organizations.show_description')</b>
                <p>{!! $organization->description !!}</p>

                @if($files = $organization->files)
                    @if($files->count())
                        <b>Файлы:</b>
                        <p>
                        @foreach($files as $file)
                            <div>
                                - <a target="_blank" href="{{ $file->link() }}" style="font-size: 11px;">
                                    {{ $file->title }}
                                </a>
                            </div>
                        @endforeach
                        </p>
                    @endif
                @endif

                        <p>
                            <b>@lang('peoples.title')</b>
                        </p>

                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <td>@lang('organizations.show_user_name')</td>
                                <td>@lang('organizations.show_user_position')</td>
                                <td>@lang('organizations.show_user_date')</td>
                                <td class="text-right">Доступ</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($connections as $connection)
                                <tr>
                                    <td>
                                        <a href="{{ route('peoples.show', $connection) }}">{{ $connection->name }}</a>
                                    </td>
                                    <td>{{ $connection->pivot->position }}</td>
                                    <td class="date-full">
                                        {{ $connection->pivot->created_at }}
                                    </td>
                                    <td class="text-right">
                                        @if($connection->user_id != $organization->user_id)
                                            @if($connection->is_admin)
                                                {!! Form::open(['url' => route('organizations.deleteAdmin', $organization), 'method'=>'post']) !!}
                                                <input type="hidden" name="user_id" value="{{ $connection->user_id }}">
                                                <button type="submit" onclick="" class="btn btn-xs btn-default" title="Удалить доступ администратора">
                                                    <i class="fa fa-key" style="color: red;"></i>
                                                </button>
                                                {!! Form::close() !!}
                                            @else
                                                {!! Form::open(['url' => route('organizations.addAdmin', $organization), 'method'=>'post']) !!}
                                                <input type="hidden" name="user_id" value="{{ $connection->user_id }}">
                                                <button type="submit" onclick="" class="btn btn-xs btn-default" title="Открыть доступ администратора">
                                                    <i class="fa fa-key" aria-hidden="true"></i>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <p>
                            <b>@lang('vacancies.title')</b>
                        </p>

                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <td>@lang('vacancies.col_name')</td>
                                <td>@lang('vacancies.col_specialization')</td>
                                <td class="text-right">@lang('vacancies.col_published')</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($organization->vacancies()->published()->get() as $vacancy)
                                <tr>
                                    <td>
                                        <a href="{{ route('vacancies.show', $vacancy) }}">{{ $vacancy->name }}</a>
                                    </td>
                                    <td>
                                        @lang('vacancies.specialization_' . $vacancy->specialization)
                                    </td>
                                    <td class="text-right date-short">
                                        {{ $vacancy->published_at }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
</div>