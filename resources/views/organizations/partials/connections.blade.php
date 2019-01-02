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
            <td><a href="{{ route('peoples.show', $connection) }}">{{ $connection->name }}</a></td>
            <td>{{ $connection->pivot->position }}</td>
            <td class="date-short">{{ $connection->pivot->created_at }}</td>
            <td class="text-right">
                @can('addFullAccess', $organization)
                    @if($connection->pivot->is_owner)
                        {!! Form::open(['url' => route('organizations.deleteFullAccess', $organization), 'method'=>'post', 'class'=>'inline']) !!}
                        <input type="hidden" name="user_id" value="{{ $connection->id }}">
                        <button type="submit" onclick="" class="btn btn-xs btn-default"
                                title="Удалить доступ на управление организацией">
                            <i class="fa fa-check" style="color: red;"></i>
                        </button>
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['url' => route('organizations.addFullAccess', $organization), 'method'=>'post', 'class'=>'inline']) !!}
                        <input type="hidden" name="user_id" value="{{ $connection->id }}">
                        <button type="submit" onclick="" class="btn btn-xs btn-default"
                                title="Открыть доступ на управление организацией">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </button>
                        {!! Form::close() !!}
                    @endif
                @endcan

                @can('addAdmin', $organization)
                    @if($connection->pivot->is_admin)
                        {!! Form::open(['url' => route('organizations.deleteAdmin', $organization), 'method'=>'post', 'class'=>'inline']) !!}
                        <input type="hidden" name="user_id" value="{{ $connection->id }}">
                        <button type="submit" onclick="" class="btn btn-xs btn-default"
                                title="Удалить доступ администратора">
                            <i class="fa fa-key" style="color: red;"></i>
                        </button>
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['url' => route('organizations.addAdmin', $organization), 'method'=>'post', 'class'=>'inline']) !!}
                        <input type="hidden" name="user_id" value="{{ $connection->id }}">
                        <button type="submit" onclick="" class="btn btn-xs btn-default"
                                title="Открыть доступ администратора">
                            <i class="fa fa-key" aria-hidden="true"></i>
                        </button>
                        {!! Form::close() !!}
                    @endif
                @endcan
            </td>
        </tr>
    @endforeach
    </tbody>
</table>