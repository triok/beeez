@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas"  role="navigation">
        <div id="sidebar">
            <div class="Categories">@lang('messages.users')</div>
                <ul class="nav">
                @include('messenger.partials.flash')

                @each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')                       
                </ul>            
        </div>
    </div>
    <div class="col-xs-6 col-sm-9" id="main">
        <h2>@lang('messages.chat')</h2>
   
    </div>
</div>


@stop

@section('users')
    <div class="col-xs-6 col-sm-2 sidebar-offcanvas" id="sidebar" role="navigation"></div>
@stop