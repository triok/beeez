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

                @include('organizations.partials.connections')

                @include('organizations.partials.vacancies')
            </div>
        </div>
    </div>
</div>