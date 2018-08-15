@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        @each('messenger.partials.messages', $thread->messages, 'message')

        @include('messenger.partials.form-message')
    </div>
@stop

@section('users')
    <div class="col-xs-6 col-sm-2 sidebar-offcanvas" id="sidebar" role="navigation">
        <ul class="nav" style="margin-top: 0">
            @foreach($threads as $row)
                <li>
                    <div style="display: flex; justify-content: space-between;">
                        <div class="media">
                            <a class="pull-left"
                               href="/messages/{{ $row->id }}">

                                <img src="{{$row->participant()->getStorageDir() . $row->participant()->avatar}}"
                                     class="img-thumbnail"
                                     style="width: 40px; height: 40px;">
                            </a>

                            <div class="media-body">
                                @if($thread->id == $row->id)
                                    <a style="color: #000;font-weight:bold;"
                                       href="/messages/{{ $row->id }}">{{ $row->participant()->name }}</a>
                                @else
                                    <a href="/messages/{{ $row->id }}">{{ $row->participant()->name }}</a>
                                @endif

                                    <?php $count = Auth::user()->unreadMessagesCountForThread($row->id); ?>
                                    @if($count > 0)
                                        <span class="label label-danger">{{ $count }}</span>
                                    @endif
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@stop
