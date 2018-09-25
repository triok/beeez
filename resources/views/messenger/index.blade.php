@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" role="navigation">
            <div id="sidebar">
                <h2>Чаты</h2>

                <a href="{{ route('threads.create') }}" class="btn btn-xs btn-success">Создать групповой чат</a>

                <div class="nav">
                    @each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-9" id="main">
            @lang('messages.partial.nothreads')
        </div>
    </div>
@stop

@section('users')
    <div class="col-xs-6 col-sm-2 sidebar-offcanvas" id="sidebar" role="navigation"></div>
@stop