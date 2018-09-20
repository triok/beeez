@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" role="navigation">
            <div id="sidebar">
                <ul class="nav">
                    @each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')
                </ul>
            </div>
        </div>

        <div class="col-xs-6 col-sm-9" id="main">
            @if($thread->isGroupChat() && $thread->user_id == auth()->user()->id)
                <a href="{{ route('threads.edit', $thread) }}" class="btn btn-default btn-xs pull-right">
                    <i class="fa fa-pencil"></i> Изменить
                </a>

                {!! Form::open(['url' => route('threads.destroy', $thread), 'method'=>'delete', 'class' => 'pull-right', 'style' => 'display:inline-block;margin-right: 5px;']) !!}
                <button type="submit" class="btn btn-xs btn-danger">
                    <i class="fa fa-trash"></i> Удалить
                </button>
                {!! Form::close() !!}
            @endif

            @if($thread->isGroupChat())
            <h1>{{ $thread->title() }}</h1>

            <p>{!! $thread->description !!}</p>

            <hr>
            @endif

            @each('messenger.partials.messages', $messages, 'message')

                {!! $messages->links() !!}

            @include('messenger.partials.form-message')
        </div>
    </div>
@stop