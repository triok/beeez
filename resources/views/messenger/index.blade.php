@extends('layouts.app')

@section('content')
    @include('messenger.partials.flash')

    @each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')
@stop

@section('users')
    <div class="col-xs-6 col-sm-2 sidebar-offcanvas" id="sidebar" role="navigation"></div>
@stop