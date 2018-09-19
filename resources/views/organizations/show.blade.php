@extends('layouts.app')
@section('content')
<div class="container" id="main">
    <div class="row">
        <div class="col-md-12">
            <hr>

            <div style="margin-bottom: 5px;">
                <a href="{{ route('organizations.index') }}">
                    <span><i class="fa fa-arrow-left"></i> @lang('organizations.back_to_list')</span>
                </a>

                @if($organization->user_id == auth()->user()->id)
                <a href="{{ route('organizations.edit', $organization) }}" class="btn btn-default btn-xs pull-right">
                    <i class="fa fa-pencil"></i> @lang('organizations.edit')
                </a>
                @endif

                @if(auth()->user()->email == config('organization.admin') && $organization->status == 'moderation')
                    {!! Form::open(['url' => route('organizations.approve', $organization), 'method'=>'patch', 'class' => 'pull-right', 'style' => 'display:inline-block;margin-right: 5px;']) !!}
                    <button type="submit" class="btn btn-xs btn-success">Approve</button>
                    {!! Form::close() !!}

                    {!! Form::open(['url' => route('organizations.reject', $organization), 'method'=>'patch', 'class' => 'pull-right', 'style' => 'display:inline-block;margin-right: 5px;']) !!}
                    <button type="submit" class="btn btn-xs btn-danger">Reject</button>
                    {!! Form::close() !!}
                @endif
            </div>

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

                            <hr>

                            <table class="table table-responsive">
                                <thead>
                                <tr>
                                    <td>@lang('organizations.show_user_name')</td>
                                    <td>@lang('organizations.show_user_position')</td>
                                    <td class="text-right">@lang('organizations.show_user_date')</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($connections as $connection)
                                <tr>
                                    <td><a href="{{ route('peoples.show', $connection->user) }}">{{ $connection->user->name }}</a></td>
                                    <td>{{ $connection->position }}</td>
                                    <td class="text-right">{{ \Carbon\Carbon::parse($connection->created_at)->format('d M, Y') }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection