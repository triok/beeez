@extends('layouts.app')

@section('content')
    <div class="container-fluid" id="notifications">
        <h2>Уведомления</h2>

        <hr>

        <div class="row">
            @foreach($notifications as $notification)
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="display: inline-block;">
                                {{ $notification->type }}
                            </h3>
                            <div class="pull-right">
                                <span class="date-short">
                                    {{ $notification->created_at }}
                                </span>
                            </div>
                        </div>
                        <div class="panel-body">
                            Вас приняли в команду "название" на должность "название должности".
                        </div>
                        <div class="panel-footer">
                            <form class="form-inline" style="display: inline-block">
                                <button type="submit" class="btn btn-success btn-sm">Принять</button>
                            </form>

                            <form class="form-inline" style="display: inline-block">
                                <button type="submit" class="btn btn-danger btn-sm">Отклонить</button>
                            </form>

                            <form class="form-inline" style="display: inline-block">
                                <button type="submit" class="btn btn-default btn-sm">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
